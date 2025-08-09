<?php
namespace App\Http\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Transaction;

class Show extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $client; // مهم: خليه public عادي لو السيرفر أقل من PHP 8.1
    public string $tab = 'overview';

    public $projectSearch = '';
    public $offerSearch   = '';
    public $trxSearch     = '';

    public $stats = [
        'projects' => 0,
        'offers'   => 0,
        'offers_open' => 0,
        'offers_closed' => 0,
        'collections_sum' => 0.00,
    ];

    public function mount(Client $client)
    {
        $this->client = $client;

        $this->stats['projects'] = $client->projects()->count();

        $offersQuery = Offer::where('client_id', $client->id);
        $this->stats['offers']        = (clone $offersQuery)->count();
        $this->stats['offers_open']   = (clone $offersQuery)->whereIn('status', ['active','pending','waiting'])->count();
        $this->stats['offers_closed'] = (clone $offersQuery)->whereIn('status', ['closed','expired','rejected'])->count();

        $this->stats['collections_sum'] = (float) Transaction::where('client_id', $client->id)
            ->where('type', 'تحصيل')
            ->sum('amount');
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $projects = Project::where('client_id', $this->client->id)
            ->when($this->projectSearch, function ($q) {
                $term = "%{$this->projectSearch}%";
                $q->where(function ($w) use ($term) {
                    $w->where('name', 'like', $term)
                      ->orWhere('description', 'like', $term)
                      ->orWhere('project_type', 'like', $term)
                      ->orWhere('status', 'like', $term);
                });
            })
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'projects_page');

        $offers = Offer::with('project')
            ->where('client_id', $this->client->id)
            ->when($this->offerSearch, function ($q) {
                $term = "%{$this->offerSearch}%";
                $q->where(function ($w) use ($term) {
                    $w->where('status', 'like', $term)
                      ->orWhere('description', 'like', $term)
                      ->orWhere('details', 'like', $term);
                });
            })
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'offers_page');

        $transactions = Transaction::with(['account','item'])
            ->where('client_id', $this->client->id)
            ->when($this->trxSearch, function ($q) {
                $term = "%{$this->trxSearch}%";
                $q->where(function ($w) use ($term) {
                    $w->where('notes', 'like', $term)
                      ->orWhere('collection_type', 'like', $term)
                      ->orWhere('type', 'like', $term);
                });
            })
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'trx_page');

        return view('livewire.clients.show', [
            'projects'     => $projects,
            'offers'       => $offers,
            'transactions' => $transactions,
        ]);
    }
}

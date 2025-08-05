<?php
namespace App\Http\Controllers\Application_settings;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storecurrencies;
use App\models\countries;
use App\models\currencies ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class currenciesController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $Countries=Countries::all();
    $currencieses=currencies::all();
  return view('settings.currencies',compact('currencieses','Countries'));

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Storecurrencies $request)
  {
    if(currencies::where('name->ar',$request->name_ar)->orwhere('name->en',$request->name_en)->exists())
    {
        return  redirect()->back()->withErrors([trans('currencies_trans.existes') ]);
    }
    try
    {
        $validated = $request->validated();
        $currencieses=new currencies();
        $currencieses->name=['en'=>$request->name_en,'ar'=>$request->name_ar];
        $currencieses->default=$request->default;
        $currencieses->status=1;
        $currencieses->id_country=$request->id_country;
        $currencieses->account_id=(Auth::user()->id_account);
        $currencieses->save();
        session()->flash('add');
        return redirect()->route('currencies.index');

    }catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Storecurrencies $request)
  {
    try
    {
    $validated = $request->validated();
    $currencieses= currencies::findorFail($request->id);
    $currencieses->update([
        $currencieses->name=['en'=>$request->name_en,'ar'=>$request->name_ar],
        $currencieses->id_country=$request->id_country,
        $currencieses->status=$request->status,
        $currencieses->default=$request->default,


    ]);
    session()->flash('edit_m');
    return redirect()->route('currencies.index');

    }
    catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    try
    {

      $currencieses= currencies::findorFail($request->id)->delete();
      Alert::success( '', trans('currencies_trans.savesuccess'));
      return redirect()->route('currencies.index');
    }
    catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }
  }

}

?>

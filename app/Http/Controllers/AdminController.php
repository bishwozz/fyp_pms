<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pms\MstItem;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {

        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        if (!backpack_user()->isSystemUser()) {

            $this->data['stores'] =  0;

            $this->data['items'] = MstItem::where('is_active', 1)
                ->where('client_id', backpack_user()->client_id)->count() ?? 0;

            $this->data['users'] = User::where('client_id', backpack_user()->client_id)->count() ?? 0;

            $this->data['total_barcodes'] = 0;

            $this->data['active_barcodes'] = 0;

            $this->data['inactive_barcodes'] = 0;
        } else {

            $this->data['organizations'] = 0;

            $this->data['stores'] =  0;

            $this->data['items'] = MstItem::where('is_active', 1)->count() ?? 0;

            $this->data['users'] = User::all()->count() ?? 0;

            $this->data['total_barcodes'] = 0;

            $this->data['active_barcodes'] = 0;

            $this->data['inactive_barcodes'] = 0;
        }

        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }

    public function send() 
    {
    	$user = backpack_user();
  
        $data = [
            'greeting' => 'Hi '.$user->name.',',
            'body' => 'This is the project assigned to you.',
            'thanks' => 'Thank you this is from codeanddeploy.com',
            'actionText' => 'View Project',
            'actionURL' => url('/'),
        ];
  
        Notification::send($user, new NewMail($data));
   
    }
}

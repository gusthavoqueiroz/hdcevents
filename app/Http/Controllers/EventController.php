<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event; //importando o Model Event 

use App\Models\User;

class EventController extends Controller
{
  
    public function index() {

        $search = request('search');

        if($search){

            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        } else {
            $events = Event::all(); //pegar todos os eventos do banco
        }

        return view('welcome',['events' => $events, 'search' => $search]);
        }

    
    public function create() {
        return view('events.create');
    }


    public function contact() {
        return view('contact');
    }


    public function products() {
        $busca = request('search');
        return view('products', ['busca' => $busca]);
    }

    
    public function product($id = null) {
        return view('product', ['id' => $id ]);
    }

    public function store(Request $request) {  //vai receber todos os dados do formulário 
       //criar um objeto com os dados do request 
        $event = new Event; //variável = new Event -> model

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items; //receber os items do criar evento


        //image upload
        
        if($request->hasfile('image') && $request->file('image')->isValid()){

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
        
            $requestImage->move(public_path('img/events'), $imageName);
        
            $event->image = $imageName;
        }


        $user = auth()->user();
        $event->user_id = $user->id;


        $event->save(); //salvar dados no bd

        //redirecionar o usuário para outra página
        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id){

        $event = Event::findOrFail($id);

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner]);
    }

    public function dashboard(){

        $user = auth()->user();

        $events = $user->events;

        return view('events.dashboard', ['evenst' => $events]);
    }
}




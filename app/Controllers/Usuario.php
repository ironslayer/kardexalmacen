<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Usuario extends BaseController

{

    protected $usuario;
    protected $reglas, $reglaslogin, $reglascambia;

    public function __construct()
    {
        $this->usuario = new UsuarioModel();
        helper(['form']);

        $this->reglas = [
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'ci' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'cargo' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'usuario' => [
                'rules' => 'required|is_unique[usuario.usuario]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'is_unique' => 'El campo {field} debe ser unico.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ]
        ];

        $this->reglaslogin = [
            'usuario' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ]
        ];

        $this->reglascambia = [
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ]
        ];
    }

    public function index($activo=1)
    {
        $info = $this->usuario->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Usuarios', 'datos' => $info];

        echo view('header');
        echo view('usuario/usuario',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Usuario'];

        echo view('header');
        echo view('usuario/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {

            // $password = $_POST['password'];
            $password = $this->request->getPost('password');

            // echo $this->request->getPost('password');

            // $password = var_export($this->request->getPost('password'));


            $hash = password_hash($password.'', PASSWORD_DEFAULT);
            

            $this->usuario->save([
                'nombre_usuario' => $this->request->getPost('nombre'),
                'ci' => $this->request->getPost('ci'),
                'cargo' => $this->request->getPost('cargo'),
                'usuario' => $this->request->getPost('usuario'),
                'password' => $hash
            ]);
            return redirect()->to(base_url() . 'usuario');

        } else {
            $data = ['titulo' => 'Agregar Usuario', 'validation' => $this->validator];

            echo view('header');
            echo view('usuario/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id_usuario, $valid=null)
    {
        $informacion = $this->usuario->where('id_usuario', $id_usuario)->first();
        if($valid != null){
            $data = ['titulo' => 'Editar Usuario', 'datos' => $informacion, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar Usuario', 'datos' => $informacion];
        }

        echo view('header');
        echo view('usuario/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {

            $password = $this->request->getPost('password');

            $hash = password_hash($password.'', PASSWORD_DEFAULT);

            $this->usuario->update($this->request->getPost('id'), [
                'nombre_usuario' => $this->request->getPost('nombre'),
                'ci' => $this->request->getPost('ci'),
                'cargo' => $this->request->getPost('cargo'),
                'usuario' => $this->request->getPost('usuario'),
                'password' => $hash
           
            ]);
            return redirect()->to(base_url() . 'usuario');
        }else{
            return $this->editar($this->request->getPost('id'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->usuario->update($id,['activo'=>0]);
        return redirect()->to(base_url().'usuario');
    }

    public function eliminados($activo=0)
    {
        $info = $this->usuario->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Usuarios Eliminados', 'datos' => $info];

        echo view('header');
        echo view('usuario/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->usuario->update($id,['activo'=>1]);
        return redirect()->to(base_url().'usuario');
    }

    public function login()
    {
        echo view('login');
    }

    public function valida()
    {
        if ($this->request->is('post') && $this->validate($this->reglaslogin)) {
            $usuario = $this->request->getPost('usuario');
            $password = $this->request->getPost('password');
            $datosUsuario = $this->usuario->where('usuario', $usuario)->first();
            
            if($datosUsuario != null){
                if(password_verify($password.'', $datosUsuario['password'])){
                    $datosSesion = [
                        'id_usuario' => $datosUsuario['id_usuario'],
                        'nombre_usuario' => $datosUsuario['nombre_usuario'],
                        'ci' => $datosUsuario['ci'],
                        'cargo' => $datosUsuario['cargo'],
                        'usuario' => $datosUsuario['usuario']

                    ];
                    $session = session();
                    $session->set($datosSesion);
                    return redirect()->to(base_url().'contenido');
                }else{
                    $data['error'] = "Las contraseñas no coinciden.";
                    echo view('login', $data);
                }
            }else{
                $data['error'] = "El usuario no existe.";
                echo view('login', $data);

            }

        }else{
            $data = ['validation' => $this->validator];
            echo view('login', $data);
        }
    }

    public function logout(){

        $session = session();
        $session->destroy(); //session_destroy
        return redirect()->to(base_url());
    }

    public function cambia_password(){

        $session = session();
        $usuario = $this->usuario->where('id_usuario', $session->id_usuario)->first();
        $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario];

        echo view('header');
        echo view('usuario/cambia_password',$data);
        echo view('footer');
    }

    public function actualizar_password(){
        if ($this->request->is('post') && $this->validate($this->reglascambia)) {

            $session = session();
            $idUsuario = $session->id_usuario;

            $password = $this->request->getPost('password');
            $hash = password_hash($password.'', PASSWORD_DEFAULT);
            

            $this->usuario->update($idUsuario, ['password' => $hash]);
            
            $usuario = $this->usuario->where('id_usuario', $session->id_usuario)->first();
            $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario, 'mensaje' => 'Contraseña actualizada.'];
    
            echo view('header');
            echo view('usuario/cambia_password',$data);
            echo view('footer');

        } else {
            // return $this->cambia_password($this->request->getPost('id'), $this->validator);
            $session = session();
            $usuario = $this->usuario->where('id_usuario', $session->id_usuario)->first();
            $data = ['titulo' => 'Cambiar contraseña', 'usuario' => $usuario, 'validation' => $this->validator];

            echo view('header');
            echo view('usuario/cambia_password',$data);
            echo view('footer');
        }
    }

}

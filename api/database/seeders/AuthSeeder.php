<?php

namespace Database\Seeders;

use App\Models\AuthPage;
use App\Models\AuthPageForm;
use App\Models\AuthPageLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagePath = 'public/assets/img/';
        AuthPage::create([
            'title' => 'Log In',
            'image' => Storage::putFileAs('assets/img/other', $imagePath . 'other/Rectangle2.svg', 'Rectangle2.svg'),
            'logo' => Storage::putFileAs('assets/img/logo', $imagePath . 'logo/Educacao_Logo_Vector.svg', 'Educacao_Logo_Vector.svg'),
        ]);
        AuthPageLink::create([
            'auth_page_id' => 1,
            'title' => 'Entrar',
            'url' => '/login',
        ]);
        AuthPageLink::create([
            'auth_page_id' => 1,
            'title' => 'Esqueceu a palavra passe ?',
            'url' => '/forget_password',
        ]);
        AuthPageForm::create([
            'auth_page_id' => 1,
            'label' => 'Email',
            'field' => 'email',
            'type' => 'email',
            'placeholder' => 'email',
        ]);
        AuthPageForm::create([
            'auth_page_id' => 1,
            'label' => 'Senha',
            'field' => 'password',
            'type' => 'password',
            'placeholder' => 'password',
        ]);
        AuthPage::create([
            'title' => 'Redifinir Senha',
            'description' => 'Digite seu e-mail e enviaremos um link para que você possa recuperar sua senha.',
            'image' => Storage::putFileAs('assets/img/other', $imagePath . 'other/Rectangle2.svg', 'Rectangle2.svg'),
            'logo' => Storage::putFileAs('assets/img/logo', $imagePath . 'logo/Educacao_Logo_Vector.svg', 'Educacao_Logo_Vector.svg'),
        ]);
        AuthPageLink::create([
            'auth_page_id' => 2,
            'title' => 'Contacte-nos',
            'url' => '/contact-us',
        ]);
        AuthPageLink::create([
            'auth_page_id' => 2,
            'title' => 'Próximo',
            'url' => '/next',
        ]);
        AuthPageForm::create([
            'auth_page_id' => 2,
            'label' => 'Email',
            'field' => 'Email ou nome do utilizador',
            'type' => 'email',
            'placeholder' => 'email',
        ]);
        AuthPage::create([
            'title' => 'CADASTRAR PALAVRA-PASSE',
            'description' => ' Para lorepsum@gmail.com',
            'image' => Storage::putFileAs('assets/img/other', $imagePath . 'other/Rectangle2.svg', 'Rectangle2.svg'),
            'logo' => Storage::putFileAs('assets/img/logo', $imagePath . 'logo/Educacao_Logo_Vector.svg', 'Educacao_Logo_Vector.svg'),
        ]);
        AuthPageLink::create([
            'auth_page_id' => 3,
            'title' => 'Entrar',
            'url' => '/dashboard',
        ]);
        AuthPageLink::create([
            'auth_page_id' => 1,
            'title' => 'Ir para Log In',
            'url' => '/login',
        ]);
        AuthPageForm::create([
            'auth_page_id' => 3,
            'label' => 'Palavra-Passe',
            'field' => 'password',
            'type' => 'password',
            'placeholder' => 'Palavra-Passe',
        ]);
        AuthPageForm::create([
            'auth_page_id' => 3,
            'label' => 'Nova Palvra-Passe',
            'field' => 'new_password',
            'type' => 'password',
            'placeholder' => 'Nova Palvra-Passe',
        ]);

    }
}

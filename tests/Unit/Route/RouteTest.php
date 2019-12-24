<?php

namespace Tests\Unit\Route;

use Tests\TestCase;

use App\Models\User;
use Laravel\Passport\Passport;

class RouteTest extends TestCase
{
    /**
     *  Get authenticated user
     */
    protected function authenticatedUser($role = "Admin"){
        $user = Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );
        $user->role = $role;
        return $user;
    }

    /**
     * with this code you can test all get page without params (index,create,etc...)
     *
     * @return void
     */

    public function testLoginRoutes()
    {
        echo PHP_EOL.PHP_EOL.'[43m Route Test:   [0m';

        echo PHP_EOL.PHP_EOL.'[44m testLoginRoutes:   [0m';
        $appURL = env('APP_URL');

        $urls = [
            '/',
            '/login',
            '/logout',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            if((int)$response->status() === 200 OR (int)$response->status() === 302){
                echo PHP_EOL.'[42m OK '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(true);
            }else{
                echo PHP_EOL.'[41m Failed '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(false);
            }
        }
    }
    /**
     * Test authenticated pages
     *
     */

    public function testViewRoutes()
    {
        echo PHP_EOL.PHP_EOL.'[44m testViewRoutes:   [0m';
        $appURL = env('APP_URL');

        $newUser = factory(User::class)->create();
        $this->be($newUser);

        $urls = [
            '/user',
            '/profile',
            '/game',

        ];
        /*
'/group'
        */

        foreach ($urls as $url) {
            $response = $this->get($url);
            if((int)$response->status() === 200 OR (int)$response->status() === 302){
                echo PHP_EOL.'[42m OK '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(true);
            }else{
                echo PHP_EOL.'[41m Failed '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(false);
            }
        }
    }

    /**
     * Test authenticated pages
     *
     */

    public function testApiRoutes()
    {
        echo PHP_EOL.PHP_EOL.'[44m testApiRoutes:   [0m';
        $appURL = env('APP_URL');

        $urls = [
            '/api/user',
            '/api/user/1',
            '/api/profile',
            '/api/game/1',
            '/api/basegame',
            '/api/unapprovedgames',
            '/api/approvedgames',

             '/api/game',

        ];
        /*
 '/api/group',
            '/api/group/1',
            '/api/group/1/user/1',
        */

        $this->authenticatedUser();

        foreach ($urls as $url) {
            $response = $this->get($url);
            if((int)$response->status() === 200 OR (int)$response->status() === 302){
                echo PHP_EOL.'[42m OK '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(true);
            }else{
                echo PHP_EOL.'[41m Failed '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(false);
            }
        }
    }

    /**
     * Test authenticated pages
     *
     */
    public function testPostApiRoutes()
    {
        echo PHP_EOL.PHP_EOL.'[44m testPostApiRoutes:   [0m';
        $appURL = env('APP_URL');

        $urls = [
            '/api/user',
            '/api/user/1',
            'api/profile',
            'api/game',
            'api/game/1',

        ];

        /*
 'api/group',
            'api/group/1',
            '/api/group/1/user/1',
        */

        $this->authenticatedUser();

        foreach ($urls as $url) {
            $response = $this->post($url);
            if((int)$response->status() === 200 OR (int)$response->status() === 302){
                echo PHP_EOL.'[42m OK '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(true);
            }else{
                echo PHP_EOL.'[41m Failed '.$response->status().' [0m'.$appURL . $url;
                $this->assertTrue(false);
            }
        }
    }
}

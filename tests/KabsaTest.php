<?php

namespace Awssat\Kabsa\Tests;

use Awssat\Kabsa\Traits\Kabsa;
use Awssat\Kabsa\Traits\KabsaRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Redis;
use Orchestra\Testbench\TestCase;

class KabsaTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->runTestMigrations();
    }

    /** @test * */
    public function first_test()
    {
        $user = User::create(['name' => 'hi']);

        // Grab a Role.
        $role = Role::where('label', 'admin')->first();

        // Associate them.
        $user->role()->associate($role);

        $this->assertEquals('admin', $user->role->label);
        $this->assertEquals(3, Role::count());
        $this->assertEquals('admin', Role::first()->label);
    }

    /** @test * */
    public function static_var()
    {
        $this->assertEquals([
            ['label' => 'admin'],
            ['label' => 'manager'],
            ['label' => 'user']
            ], Role::all()->toArray());


        $this->assertEquals([
            ['label' => 'admin'],
            ['label' => 'manager'],
            ['label' => 'user']
        ], Role::$kabsaCollection->toArray());


        Role::$kabsaCollection = collect([['static']]);

        //one
        $this->assertEquals([
            ['static']
        ], Role::all()->toArray());

        //two
        $this->assertEquals([
            ['static']
        ], Role::all()->toArray());

        Role::$kabsaCollection = [];

        $this->assertEquals([
            ['label' => 'admin'],
            ['label' => 'manager'],
            ['label' => 'user']
        ], Role::all()->toArray());

        $this->assertEquals([
            ['label' => 'admin'],
            ['label' => 'manager'],
            ['label' => 'user']
        ], Role::$kabsaCollection->toArray());
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
    /**
     * Run migrations for tables used for testing purposes.
     *
     * @return void
     */
    private function runTestMigrations()
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        if (! $schema->hasTable('users')) {
            $schema->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('role_label')->default('user');
                $table->timestamps();
            });
        }
    }
}

class Role extends Model
{
    use Kabsa;

    protected $rows = [
        ['label' => 'admin'],
        ['label' => 'manager'],
        ['label' => 'user'],
    ];
}

class User extends Model
{
    use KabsaRelationships;

    protected $guarded = [];
    protected $table = 'users';

    public function role()
    {
        return $this->belongsToKabsaRow(Role::class, 'label', 'role_label');
    }
}
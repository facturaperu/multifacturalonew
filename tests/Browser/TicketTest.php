<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Config, DB;

class TicketTest extends DuskTestCase
{

    use DatabaseMigrations;
    
    /**
    * @group ticket
    */
    public function testTicket()
    {
        $this->browse(function (Browser $browser) {
            

             // Seeders
             $this->artisan('db:seed', [
                '--class' => 'DatabaseSeeder'
            ]);
            
            // Login (Admin)
            $browser->visit('/')
                ->assertSee('Acceso al Sistema')
                ->type('email', 'admin@gmail.com')
                ->type('password', '123456')
                ->press('Iniciar sesión')
                ->assertPathIs('/dashboard');
            
            // Customer registration (Admin)
            $browser->press('Nuevo')
                ->assertSee('Nuevo Cliente')
                ->type('@number', '20501973522')
                ->type('@name', 'TESTS S.A.S')
                ->type('@subdomain', 'dev')
                ->type('@email', 'test@test.com')
                ->type('@password', '123456')
                ->click('@plan_id')
                ->waitFor('@plan_id')
                ->elements('.el-select-dropdown__item')[0]->click();
            
            $browser->waitForText('Guardar', 5)
                ->press('Guardar')
                ->waitForText('Cliente Registrado satisfactoriamente', 300);
            
            // Change of url (Sub-domain)
            Browser::$baseUrl = 'http://dev.'.env('APP_URL_BASE');
            
            // Login (Sub-domain)
            $browser->visit('/')
                ->type('email', 'test@test.com')
                ->type('password', '123456')
                ->press('Iniciar sesión')
                ->waitForText('Menu', 55);
            
            // Create product (Sub-domain)
            $browser->clickLink('Productos')
                ->press('Nuevo')
                ->waitForText('Nuevo Producto', 5)
                ->type('@internal_id', 'P001')
                ->type('@description', 'PEPSI')
                ->type('@item_code', '-')
                ->type('@sale_unit_price', '20')
                ->type('@purchase_unit_price', '20')
                ->press('Guardar')
                ->waitForText('Producto registrado con éxito', 5);
            
            // Create client (Sub-domain)
            $browser->clickLink('Clientes')
                ->press('Nuevo')
                ->waitForText('Nuevo Cliente', 5)
                ->click('@identity_document_type_id')
                ->waitFor('@identity_document_type_id')                
                ->elements('.el-select-identity_document_type .el-select-dropdown__item')[1]->click();                
            
            $browser->waitForText('RENIEC', 5)
                ->type('@number', '77695545')
                ->type('@name', 'Juan Perez');
           
            $browser->press('Guardar')
                ->waitForText('Cliente registrado con éxito', 5);


            //boleta          

            $browser->clickLink('Nuevo comprobante electrónico') 
                ->waitForText('Tipo de comprobante', 5)
                ->click('@document_type_id')
                ->waitFor('@document_type_id')                
                ->elements('.el-select-document_type .el-select-dropdown__item')[1]->click();  

            $browser->click('@customer_id')
                ->waitFor('@customer_id')
                ->elements('.el-select-customers .el-select-dropdown__item')[0]->click();
            
            $browser->press('+ Agregar Producto')
                ->waitForText('Agregar Producto o Servicio', 5)
                ->click('@item_id')
                ->waitFor('@item_id')
                ->elements('.el-select-items .el-select-dropdown__item')[0]->click();
            
            $browser->elements('.el-button.add')[0]->click();
            
            $browser->press('Cerrar')
                ->waitForText('Generar', 15) 
                ->press('Generar');

            
            $browser->waitForText('Comprobante: B001-1', 50)
                ->waitForText('Ir al listado', 20)
                ->elements('.el-button.list')[0]->click();



            $browser->visit('/summaries')
                    ->waitForText('Nuevo', 20)
                    ->press('Nuevo');

            $browser->waitForText('Registrar Resumen', 20)
                    ->click('@search-documents');
 

            $browser->waitForText('Guardar', 20)
                    ->click('@save-summary');
            
            /*while($browser->waitForText('Code: HTTP; Description: Bad Gateway')){
                
                $browser->waitForText('Guardar', 10)
                    ->click('@save-summary');
                
                $browser->pause(30);
            }*/
                    

            $browser->waitForText('El resumen RC-20190213-1 fue creado correctamente', 25)
                    ->assertSee('El resumen RC-20190213-1 fue creado correctamente');
           
            /*$browser->waitForText('Acciones', 20)
                    ->click('@consult-ticket');

            $browser->waitForText('El Resumen diario RC-20190213-1, ha sido aceptado', 20)
                    ->assertSee('El Resumen diario RC-20190213-1, ha sido aceptado');*/
                    
                    
                   
                // Logout (Sub-domain)
            $browser->clickLink('Administrador')
            ->waitForText('Salir', 3)
            ->clickLink('Salir')
            ->assertSee('Acceso al Sistema');
        
            // Change of url (Admin)
            Browser::$baseUrl = 'http://'.env('APP_URL_BASE');
            
            // Customer removal (Admin)
            $browser->visit('/')
                ->waitForText('Eliminar', 5)
                ->press('Eliminar')
                ->waitForText('¿Desea eliminar el registro?', 5)
                ->elements('.el-message-box .el-button--primary')[0]->click();
            
            $browser->waitForText('Se eliminó correctamente el registro', 300);
            
            // Logout (Admin)
            $browser->clickLink('Admin Instrador')
                ->waitForText('Salir', 3)
                ->clickLink('Salir')
                ->assertSee('Acceso al Sistema');

        });
    }
}

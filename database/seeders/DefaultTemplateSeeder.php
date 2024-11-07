<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class DefaultTemplateSeeder extends Seeder
{
    public function run()
    {
        // Template::create([
        //     'name' => 'Default Bootstrap Template',
        //     'html' => view('templates.default_template')->render(),
        //     'css' => ''
        // ]);

        // Template::create([
        //     'name' => 'Plantilla Base',
        //     'description' => 'Una plantilla base simple con Bootstrap',
        //     'type' => 'page',
        //     'layout' => 'one_column',
        //     'category' => 'general',
        //     'author' => 'Admin',
        //     'version' => '1.0',
        //     'content' => '
        //         <div class="container">
        //             <div class="row">
        //                 <div class="col-md-12">
        //                     <h1>Bienvenido a mi página</h1>
        //                     <p>Este es un párrafo de ejemplo.</p>
        //                 </div>
        //             </div>
        //         </div>
        //     ',
        //     'styles' => '
        //         body { font-family: Arial, sans-serif; }
        //         h1 { color: #333; }
        //     ',
        //     'components' => json_encode([
        //         'type' => 'root',
        //         'components' => [
        //             [
        //                 'type' => 'container',
        //                 'classes' => ['container'],
        //                 'components' => [
        //                     [
        //                         'type' => 'row',
        //                         'components' => [
        //                             [
        //                                 'type' => 'column',
        //                                 'classes' => ['col-md-12'],
        //                                 'components' => [
        //                                     [
        //                                         'type' => 'text',
        //                                         'content' => '<h1>Bienvenido a mi página</h1>'
        //                                     ],
        //                                     [
        //                                         'type' => 'text',
        //                                         'content' => '<p>Este es un párrafo de ejemplo.</p>'
        //                                     ]
        //                                 ]
        //                             ]
        //                         ]
        //                     ]
        //                 ]
        //             ]
        //         ]
        //     ]),
        //     'full_styles' => json_encode([
        //         [
        //             'selectors' => ['body'],
        //             'style' => ['font-family' => 'Arial, sans-serif']
        //         ],
        //         [
        //             'selectors' => ['h1'],
        //             'style' => ['color' => '#333']
        //         ]
        //     ]),
        //     'primary_color' => '#007bff',
        //     'secondary_color' => '#6c757d',
        //     'font_family' => 'Arial',
        // ]);

        Template::create([
            'name' => 'Plantilla Por Defecto',
            'description' => 'Una plantilla base simple con Bootstrap',
            // 'content' => file_get_contents(resource_path('views/templates/default_template.blade.php')),
            'styles' => file_get_contents(resource_path('views/templates/assets/default_styles.css')),
            'user_id' => 1,
            'is_default' => true,
            'folder_id' => null,
            'thumbnail' => null,
        ]);
    }
}

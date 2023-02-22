<?php
@session_start();

$form = [
    "form"=>["action"=>"#", "method"=>"POST", "enctype"=>"multipart/form-data", "class"=>"forms"],
    "fields"=>[
         //for all input field expert radio, checkbox and textarea
        ["type"=>"text","name"=>"firstname", "id"=>"firstid", "class"=>"form-control","label"=>"Firstname","placeholder"=>"Your name"],
        ["type"=>"email","name"=>"email", "id"=>"emailid", "class"=>"form-control","label"=>"Email","placeholder"=>"Your email"],
        ["type"=>"tel","name"=>"phone", "id"=>"phoneid", "class"=>"form-control","label"=>"Phone","placeholder"=>"Your phone"],
        ["type"=>"url","name"=>"url", "id"=>"rulid", "class"=>"form-control","label"=>"Website","placeholder"=>"Your Website"],
        ["type"=>"number","name"=>"number", "id"=>"numberid", "class"=>"form-control","label"=>"Total","placeholder"=>"Your total"],
        ["type"=>"file","name"=>"file", "id"=>"fileid", "class"=>"form-control","label"=>"File","placeholder"=>"Your file"],
        //for select field
        ["type"=>"select","name"=>"cars","id"=>"selectid","label"=>"Choose cars", "class"=>"form-control", "options"
        =>[
                ["value"=>"toyota","text"=>"Toyota"],
                ["value"=>"rambogini","text"=>"Ramborgini"],
                ["value"=>"hundai","text"=>"Hundai"]
            ]
        ],
        //for radios
       ["type"=>"radio", "label"=>"Gender", "radios"=>[
           ["name"=>"gender", "id"=>"radioid", "class"=>"","type"=>"radio", "value"=>"male", "label"=>"Male"],
           ["name"=>"gender", "id"=>"radioid", "class"=>"","type"=>"radio", "value"=>"female", "label"=>"Female"],
       ]],
        //for checkboxes
        ["type"=>"checkbox", "label"=>"Hobbies", "checkboxes"=>[
            ["name"=>"checkboxhobbies1", "id"=>"checkboxhobbies1", "class"=>"","type"=>"checkbox", "value"=>"dancing", "label"=>"Dancing"],
            ["name"=>"checkboxhobbies2", "id"=>"checkboxhobbies2", "class"=>"","type"=>"checkbox", "value"=>"swimming", "label"=>"Swimming"],
        ]],
        //for textarea field
        ["type"=>"textarea","name"=>"messages","id"=>"textid","cols"=>7, "rows"=>7,"class"=>"form-control","label"=>"Your message"],
    ],
    "button"=>['name'=>'submitting', 'text'=>"Submit now", "class"=>"btn btn-primary"],
];

if($_SERVER['REQUEST_METHOD'] === "POST"){
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
}

$build = \FormViewCreation\BuildeForm::build($form);
?>
<div class="container w-100">
    <div class="mt-5 w-50">
        <h1 class="text-center fs-1">Generated Form builder</h1>
        <?php echo $build; ?>
    </div>
</div>

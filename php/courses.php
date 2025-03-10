<?php
require_once './etc/config.php';

try {
    $courses = Course::findAll();
} 
catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/grid.css">
        <link rel="stylesheet" href="../css/styles.css">

        <title>Courses</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>Courses</h1>
            </div>
            <div class="width-8">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Modules</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $c) { ?>
                        <tr>
                            <td><?= $c->title ?></td>
                            <td><?= implode(' ', array_slice(explode(' ', $c->description), 0, 3)) . "..."  ?></td>
                            <td><?= $c->code ?></td>
                            <td><?= $c->department()->title ?></td>
                            <td><?= '<ul class="form-list"><li>' . implode("</li><li>", array_map(fn($m) => $m->title, $c->modules())) . "</li></ul>" ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="width-4">
                <h2>Add Course</h2>
                <form id="form-course">
                    <div class="form-group">
                        <label class="form-label">Title: </label>
                        <input type="text" name="title" id="title" class="form-input" value="">
                        <div class="error" id="error-title"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description: </label>
                        <textarea name="description" id="description" class="form-textarea"></textarea>
                        <div class="error" id="error-description"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code: </label>
                        <input type="text" name="code" id="code" class="form-input" value="">
                        <div class="error" id="error-code"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department: </label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">Please choose...</option>
                            <?php foreach (Department::findAll() as $d) { ?>
                            <option value="<?= $d->id ?>"><?= $d->title ?></option>
                            <?php } ?>
                        </select>
                        <div class="error" id="error-department_id"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Modules: </label>
                        <select name="module_id[]" id="module_id" class="form-select" multiple size="7">
                            <?php foreach (Module::findAll() as $m) { ?>
                            <option value="<?= $m->id ?>"><?= $m->title ?></option>
                            <?php } ?>
                        </select>
                        <div class="error" id="error-module_id"></div>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>
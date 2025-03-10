<?php
require_once './etc/config.php';

try {
    $employees = Employee::findAll();
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

        <title>Employees</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>Employees</h1>
            </div>
            <div class="width-8">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>PPSN</th>
                            <th>Salary</th>
                            <th>Department</th>
                            <th>Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $e) { ?>
                        <tr>
                            <td><?= $e->name ?></td>
                            <td><?= $e->ppsn  ?></td>
                            <td><?= $e->salary ?></td>
                            <td><?= $e->department()->title ?></td>
                            <td><?= '<ul class="form-list"><li>' . implode("</li><li>", array_map(fn($p) => $p->title, $e->projects())) . "</li></ul>" ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="width-4">
                <h2>Add Employee</h2>
                <form id="form-employee">
                    <div class="form-group">
                        <label class="form-label">Name: </label>
                        <input type="text" name="name" id="name" class="form-input" value="">
                        <div class="error" id="error-name"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PPSN: </label>
                        <textarea name="ppsn" id="ppsn" class="form-textarea"></textarea>
                        <div class="error" id="error-ppsn"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary: </label>
                        <input type="text" name="salary" id="salary" class="form-input" value="">
                        <div class="error" id="error-salary"></div>
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
                        <label class="form-label">Projects: </label>
                        <select name="project_id[]" id="project_id" class="form-select" multiple size="7">
                            <?php foreach (Project::findAll() as $m) { ?>
                            <option value="<?= $m->id ?>"><?= $m->title ?></option>
                            <?php } ?>
                        </select>
                        <div class="error" id="error-project_id"></div>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
require_once './etc/config.php';

try {
    $profiles = Profile::findAll();
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

        <title>Profiles</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>Profiles</h1>
            </div>
            <div class="width-8">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Category</th>
                            <th>Experience</th>
                            <th>Languages</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profiles as $p) { ?>
                        <tr>
                            <td><?= $p->name ?></td>
                            <td><?= $p->age ?></td>
                            <td><?= $p->category ?></td>
                            <td><?= $p->experience ?></td>
                            <td><?= $p->languages ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="width-4">
                <h2>Add Profile</h2>
                <form id="form-profile">
                    <div class="form-group">
                        <label class="form-label">Name: </label>
                        <input type="text" name="name" id="name" class="form-input" value="">
                        <div class="error" id="error-name"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age: </label>
                        <input type="text" name="age" id="age" class="form-input" value="">
                        <div class="error" id="error-age"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category: </label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Please choose...</option>"
                            <option value="1">Sport</option>
                            <option value="2">Music</option>
                            <option value="3">Movie</option>
                        </select>
                        <div class="error" id="error-category"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience:</label>
                        <div class="radio-group">
                            <div>
                                <input type="radio" name="experience" id="experience-novice" value="novice">
                                <label for="experience-novice">Novice</label>
                            </div>
                            <div>
                                <input type="radio" name="experience" id="experience-competent" value="competent">
                                <label for="experience-competent">Competent</label>
                            </div>
                            <div>
                                <input type="radio" name="experience" id="experience-expert" value="expert">
                                <label for="experience-expert">Expert</label>
                            </div>
                        </div>
                        <div class="error" id="error-experience"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Languages:</label>
                        <div class="radio-group">
                            <div>
                                <input type="checkbox" name="language[]" id="language-english" value="english">
                                <label for="language-english">Novice</label>
                            </div>
                            <div>
                                <input type="checkbox" name="language[]" id="language-irish" value="irish">
                                <label for="language-irish">Competent</label>
                            </div>
                            <div>
                                <input type="checkbox" name="language[]" id="language-spanish" value="spanish">
                                <label for="language-spanish">Expert</label>
                            </div>
                        </div>
                        <div class="error" id="error-language"></div>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>
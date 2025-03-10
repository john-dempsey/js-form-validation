<?php
require_once './etc/config.php';

try {
    $books = Book::findAll();
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

        <title>Books</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>Books</h1>
            </div>
            <div class="width-8">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>ISBN</th>
                            <th>Price</th>
                            <th>Publisher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $b) { ?>
                        <tr>
                            <td><?= $b->title ?></td>
                            <td><?= '<ul class="form-list"><li>' . implode("</li><li>", array_map(fn($a) => $a->name, $b->authors())) . "</li></ul>" ?></td>
                            <td><?= $b->isbn ?></td>
                            <td><?= $b->price ?></td>
                            <td><?= $b->publisher()->title ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="width-4">
                <h2>Add Book</h2>
                <form id="form-book">
                    <div class="form-group">
                        <label class="form-label">Title: </label>
                        <input type="text" name="title" id="title" class="form-input" value="">
                        <div class="error" id="error-title"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Authors: </label>
                        <select name="author_id[]" id="author_id" class="form-select" multiple size="7">
                            <?php foreach (Author::findAll() as $a) { ?>
                            <option value="<?= $a->id ?>"><?= $a->name ?></option>
                            <?php } ?>
                        </select>
                        <div class="error" id="error-author_id"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ISBN: </label>
                        <input type="text" name="isbn" id="isbn" class="form-input" value="">
                        <div class="error" id="error-isbn"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price: </label>
                        <input type="text" name="price" id="price" class="form-input" value="">
                        <div class="error" id="error-price"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Publisher: </label>
                        <select name="publisher_id" id="publisher_id" class="form-select">
                            <option value="">Please choose...</option>
                            <?php foreach (Publisher::findAll() as $p) { ?>
                            <option value="<?= $p->id ?>"><?= $p->title ?></option>
                            <?php } ?>
                        </select>
                        <div class="error" id="error-publisher_id"></div>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
$file = "container.json";
if (file_exists($file)) {
    $fileContent = file_get_contents($file);
    $data = json_decode($fileContent, true);
    if ($data !== null) {

        // Handle moving items up and down
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['moveUp'])) {
                $index = $_POST['moveUp'];
                if ($index > 0 && $index < count($data)) {
                    $item = $data[$index];
                    array_splice($data, $index, 1);
                    array_splice($data, $index - 1, 0, array($item));
                }
            } elseif (isset($_POST['moveDown'])) {
                $index = $_POST['moveDown'];
                if ($index >= 0 && $index < count($data) - 1) {
                    $item = $data[$index];
                    array_splice($data, $index, 1);
                    array_splice($data, $index + 1, 0, array($item));
                }
            } elseif (isset($_POST['commit'])) {
                // Handle updating the JSON data
                $updatedData = array();
                foreach ($data as $item) {
                    $title = isset($_POST['title-' . $item['title']]) ? $_POST['title-' . $item['title']] : $item['title'];
                    $price = isset($_POST['price-' . $item['title']]) ? $_POST['price-' . $item['title']] : $item['price'];
                    $availability = isset($_POST['availability-' . $item['title']]) ? $_POST['availability-' . $item['title']] : $item['availability'];

                    $updatedData[] = array(
                        'imagePath' => $item['imagePath'],
                        'title' => $title,
                        'price' => $price,
                        'availability' => $availability,
                    );
                }

                // Save the updated data to container.json
                file_put_contents($file, json_encode($updatedData, JSON_PRETTY_PRINT));
                header("Location: your_current_page.php"); // Redirect to page refresh after committing changes
                exit();
            }
        }

        echo '<h2 style="color: black;">Previous Data</h2>';
        echo '<form action="" method="post">';
        echo '<table id="data-table" style="color: black; border-collapse: collapse; width: 100%;">';
        echo '<tr><th style="border: 1px solid black;">Image</th><th style="border: 1px solid black;">Title</th><th style="border: 1px solid black;">Price</th><th style="border: 1px solid black;">Availability</th><th style="border: 1px solid black;">Actions</th></tr>';
        foreach ($data as $index => $item) {
            echo '<tr>';
            echo '<td style="border: 1px solid black;">';
            $imagePath = isset($item['imagePath']) ? $item['imagePath'] : '';
            echo '<img src="' . $imagePath . '" alt="Product Image" width="100" height="100">';
            echo '</td>';
            echo '<td style="border: 1px solid black;"><input type="text" name="title-' . $item['title'] . '" value="' . $item['title'] . '"></td>';
            echo '<td style="border: 1px solid black;"><input type="text" name="price-' . $item['title'] . '" value="' . $item['price'] . '"></td>';
            echo '<td style="border: 1px solid black;"><input type="text" name="availability-' . $item['title'] . '" value="' . $item['availability'] . '"></td>';
            echo '<td style="border: 1px solid black;">';
            echo '<button class="move-up-btn" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;" type="submit" name="moveUp" value="' . $index . '">&#8593;</button>&nbsp;';
            echo '<button class="move-down-btn" style="background-color: blue; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;" type="submit" name="moveDown" value="' . $index . '">&#8595;</button>&nbsp;';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<button id="commit-filter-btn" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-left: 85%; margin-top: 5%;" type="submit" name="commit">Commit Filter</button>';
        echo '</form>';
    } else {
        echo 'Error decoding JSON file';
    }
} else {
    echo 'No data found';
}
?>

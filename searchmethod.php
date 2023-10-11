<script>
$(document).ready(function() {
  $('.move-up-btn').click(function() {
    var row = $(this).closest('tr');
    if (row.index() > 1) {
      row.insertBefore(row.prev());
    }
  });
  $('.move-down-btn').click(function() {
    var row = $(this).closest('tr');
    row.insertAfter(row.next());
  });
  $('#commit-filter-btn').click(function() {
    var tableData = [];
    $('#data-table tbody tr').each(function() {
      var row = $(this);
      var item = {
        title: row.find('td:nth-child(2)').text().trim(),
        price: row.find('td:nth-child(3)').text().trim(),
        availability: row.find('td:nth-child(4)').text().trim(),
        imagePath: row.find('img').attr('src')
      };
      tableData.push(item);
    });
    var jsonData = JSON.stringify(tableData);
    $.ajax({
      type: 'POST',
      url: 'save.php', // Replace this with the correct URL to handle the request.
      data: { data: jsonData },
      success: function(response) {
        console.log(response);
      }
    });
  });
});
</script>
<button id="refreshButton" class="openbtn">Refresh</button>
<div class="modal fade" id="search" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel" style="color: black;">search modification modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 100vh;">
                     <font color="black"><b>Admin you can upload images using the 'Choose file' button and press submit to sync your changes to search function</b></font>
             <br><br><div id="imagePreview"></div>
             <form action="store_data.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="myImage" accept="image/png, image/gif, image/jpeg" id="myImageInput" />
                <input type="text" name="title" placeholder="Product title">
                <input type="number" name="price" placeholder="Product price">
                <input type="number" name="availability" placeholder="Product availability/stock count"><br><br>
                <input type="submit" value="Submit">
            </form>
                 <?php
$file = "container.json";
if (file_exists($file)) {
    $fileContent = file_get_contents($file);
    $data = json_decode($fileContent, true);
    if ($data !== null) {
        while (!empty($data) && $data[0]['title'] === '') {
            array_shift($data);
        }
        echo '<h2 style="color: black;">Previous Data</h2>';
        echo '<table id="data-table" style="color: black; border-collapse: collapse; width: 100%;">';
        echo '<tr><th style="border: 1px solid black;">Image</th><th style="border: 1px solid black;">Title</th><th style="border: 1px solid black;">Price</th><th style="border: 1px solid black;">Availability</th><th style="border: 1px solid black;">Actions</th></tr>';
        foreach ($data as $item) {
            echo '<tr>';
            echo '<td style="border: 1px solid black;">';
            $imagePath = isset($item['imagePath']) ? $item['imagePath'] : '';
            echo '<img src="' . $imagePath . '" alt="Product Image" width="100" height="100">';
            echo '</td>';
            echo '<td style="border: 1px solid black;">' . $item['title'] . '</td>';
            echo '<td style="border: 1px solid black;">' . $item['price'] . '</td>';
            echo '<td style="border: 1px solid black; text-align: center;">' . $item['availability'] . '</td>';
            echo '<td style="border: 1px solid black;">';
            echo '<button class="move-up-btn" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">&#8593;</button>&nbsp;';
            echo '<button class="move-down-btn" style="background-color: blue; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">&#8595;</button>&nbsp;';
            echo '<form action="delete.php" method="post" style="display: inline;">';
            echo '<input type="hidden" name="title" value="' . $item['title'] . '">';
            echo '<input type="submit" value="Delete" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<button id="commit-filter-btn" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-left: 85%; margin-top: 5%;">Commit Filter</button>';
    } else {
        echo 'Error decoding JSON file';
    }
} else {
    echo 'No data found';
}
?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="post" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel" style="color: black;">posts modification modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 100vh;">
                     <font color="black"><b>Admin you can upload images using the 'Choose file' button and press submit to sync your changes to post function</b></font>
             <br><br><div id="imagePreview1"></div>
             <form action="data_block/json_object/post.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="myImage" accept="image/png, image/gif, image/jpeg" id="myImageInput1" /><br><br>
                <input type="submit" value="Submit">
            </form>
          <?php
$file = "data_block/json_object/post.json";
if (file_exists($file)) {
    $fileContent = file_get_contents($file);
    $data = json_decode($fileContent, true);
    if ($data !== null) {
        echo '<h2 style="color: black;">Previous Data</h2>';
        echo '<table id="previous-data-table" style="color: black; border-collapse: collapse; width: 100%;">';
        echo '<tr><th style="border: 1px solid black;">Image</th><th style="border: 1px solid black;">Actions</th></tr>';

        foreach ($data as $index => $item) {
            echo '<tr>';
            echo '<td style="border: 1px solid black;">';
            $fileName = $item['fileName'];
            $imagePath = "data_block/json_object/post-upload/" . $fileName;
            echo '<img src="' . $imagePath . '" alt="Product Image" width="100" height="100" style="margin-top: 10px; margin-right: 20px;">';
            echo '</td>';

            echo '<td style="border: 1px solid black; text-align: center;">';
            echo '<form action="data_block/json_object/delete.php" method="post" style="display: inline;">';
            echo '<input type="hidden" name="index" value="' . $index . '">';
            echo '<input type="submit" value="Delete">';
            echo '</form>';
            echo '</td>';

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'Error decoding JSON file';
    }
} else {
    echo 'No data found';
}
?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

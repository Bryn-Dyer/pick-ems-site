<?php 
include "functions.php";
?>

<!DOCTYPE html>
<html lang="english">
    <body>
        <label for="Query">Query by:</label>
        <select name="Query" id="Query">
            <option value="User">User</option>
            <option value="Week">Week</option>
            <option value="Season">Season</option>
        </select>
        <form id="formQuery">
            <input type="text" name="text" placeholder="Optional">
        </form>
        <button type="button" onlcick="add()">Add</button>
        <button type="button" onlcick="remove()">Remove</button>
<script>
    var formQuery = document.getElementById("formQuery");

    function add() {
        var newText = document.createElement("input");
        newText.setAttribute("type",'text')
        newText.setAttribute("name",'text')
        formQuery.appendChild(newText);
    }

    function remove() {
        var input_tags = formQuery.getElementsByTagName('input');
        if(input_tags.length > 2) {
            formQuery.removeChild(input_tags[(input_tags.length) - 1]);
        }
    }
</script>
</body>
</html>
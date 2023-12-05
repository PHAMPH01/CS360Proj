<?php
    function ben_genSearchForm($db, $userID){
        ?>
        <section class="hero">
        <h1>Let's Start Cooking!</h1>
    </section>
    <style>
    .searchForm {
    background-color: #fafad2;
    color: black;
    margin-left: 400px;
    margin-right: 400px;
    border-radius: 5px;
    padding: 5px;
    border: 20px solid #9c1e21;
    align-items: center;
    }

    #fmSearch {
        background-color: #fafad2;
        color: black;
        text-align: left;
        font-weight: bold;
        padding: 5px;
    }

    #fmSearch input{
        width: 50%;
        padding: 12px 20px;
        margin: 8px 0px;
        display: inline-block;
        border: 1px solid black;
        border-radius: 4px;
        box-sizing: border-box;
        text-align: center;
    }

    #fmSearch select{
        width: 50%;
        padding: 12px 20px;
        margin: 8px 0px;
        display: inline-block;
        border: 1px solid black;
        border-radius: 4px;
        box-sizing: border-box;
        text-align: center;
    }

    #fmSearch input[type=number]{
        width: 30%;
    }

    #fmSearch input[type=submit]{
        text-align: center;
        background-color: #9c1e21;
        color: lightgoldenrodyellow;
        width: 100%;
    }

    #fmSearch input[type=submit]:hover{
        background-color: #6e1618
    }
    </style>
    <DIV class='searchForm'>
        <FORM name='fmSearch' id='fmSearch' method='POST' action='?op=search' class='cta'>
        <!--mealtype-->
        <label for="mealType">What kind of meal do you want?</label><br>
        <select id="mealType" name="mealType">
            <option value="any">Any</option>
            <option value="Bf">Breakfast</option>
            <option value="Lun">Lunch</option>
            <option value="Din">Dinner</option>
            <option value="Des">Dessert</option>
            <option value="Sn">Snack</option>
        </select><br>
        <!--keyword-->
        <LABEL for='keyword'>Put in a keyword for your meal! (Optional)</LABEL><br>
        <INPUT type='text' id= 'keyword' name='keyword' placeholder='keyword (ie. eggs)'/><br>
        <!--calories-->
        Minimum and Maximum Calories (Optional)
        <br>
        <INPUT type="number" id="caloriesMin" name="caloriesMin" min="0">
        -
        <INPUT type="number" id="caloriesMax" name="caloriesMax" min="1"><br>

        <INPUT type='submit' id='searchSubmit' value='Look For Meals!'/></FORM>
        </DIV>
        <?php
    }

    function ben_search($db, $data){
        //first part of statement
        $stmt = ("SELECT mealID, name, description, recipe, imagePath, calories
        FROM meal
        WHERE 1=1 ");

        //mealtype
        //always set, check if not set to any
        $mealType = $data['mealType'];
        if($mealType != 'any'){
            $stmt .= "AND mealType LIKE '%$mealType%";
        }

        //keywords
        if($data['keyword'] != "") {
            $keyword = $data['keyword'];
            $stmt .= " AND name LIKE '%$keyword%'";
        }

        //calories
        //min
        if($data['caloriesMin'] != ""){
            $caloriesMin = $data['caloriesMin'];
            $stmt .= " AND calories >= $caloriesMin";
        }

        //max
        if($data['caloriesMax'] != ""){
            $caloriesMax = $data['caloriesMax'];
            $stmt .= " AND calories <= $caloriesMax";
        }
        echo "<h2>$stmt</h2>";
        //send query and verify if correct
        $res = $db->query($stmt);

        if ($res == FALSE) {
            
            header("refresh:3;url=?op=searchForm");
            printf("<H3>Error while attempting to search for meal!</H3>\n");
        }
        else{
            //send to searchTable
            ben_searchTable($res);
        }
    }

    function ben_searchTable($data){
        //make table header
        ?>
        <TABLE id="searchTable">
        <TR>
        <TH>Name</TH>
        <TH>Description</TH>
        <TH>Calories</TH>
        <TH>Select Meal</TH>
        </TR>
        <?php
        //for loop for all entries
        while ($row = $data->fetch()) {
            $name = $row['name'];
            $desc = $row['description'];
            $calories = $row['calories'];
            $mealID = $row['mealID'];
            
            //TODO, $mealID will be turned into a <button> for going to the meal (call a function?)
            
            echo "<TR><TD>$name</TD><TD>$desc</TD><TD>$calories</TD><TD>$mealID</TD></TR>\n";
         }

        //close table
        echo "</TABLE>";
    }

    function ben_genPopularTable($db){
        //some formatting
        ?>
        <section class="hero">
          <h1>Popular Meals</h1>
          <p>Try some meals others <u><i>LOVED!</i></u></p>
        </section>
        <?php
        //create SQL query

        //GOALS:
        //1) query needs to grab the avg rating of individual meals (avg rating for each meal, not avg rating overall)
        //2) query then groups by mealID (nat join meal) and sorts by highest rated (orderby)
        $stmt = "SELECT mealID, name, description, calories, (SELECT ROUND(AVG(rating))
                                                              FROM rating
                                                              WHERE meal.mealID = rating.mealID) AS rating
        FROM meal
        GROUP BY mealID
        HAVING rating >= 0
        ORDER BY rating DESC
        LIMIT 10;";

        //send query and error check
        $res = $db->query($stmt);

        if ($res == FALSE) {
            header("refresh:3;url=?op=main");
            printf("<H3>Error while attempting to search for popular meals!</H3>\n");
        }

        //make table
        ?>
        <TABLE id="searchTable">
        <TR>
        <TH>Name</TH>
        <TH>Description</TH>
        <TH>Calories</TH>
        <TH>Overall Rating</TH>
        <TH>Select Meal</TH>
        </TR>
        <?php
        //for loop for all entries
        while ($row = $res->fetch()) {
            $name = $row['name'];
            $desc = $row['description'];
            $calories = $row['calories'];
            $rating = $row['rating'];
            $mealID = $row['mealID'];
            
            //TODO, $mealID will be turned into a <button> for going to the meal (call a function?)
            
            echo "<TR><TD>$name</TD><TD>$desc</TD><TD>$calories</TD><TD>$rating</TD><TD>$mealID</TD></TR>\n";
         }

        //close table
        echo "</TABLE>";
    }
?>

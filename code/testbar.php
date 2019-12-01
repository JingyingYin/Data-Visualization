<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <meta charset="utf-8">
    <style>
        .main{
            margin-left:auto;
            margin-right:auto;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 60px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 0px 0px;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        #header {
            background-color:darkred;
            color:orange;
            height:10%;
            width:100%;
            text-align:center;
            padding:0;
            position: fixed;
            z-index:100;
        }
        #buttons {
            background-color:whitesmoke;
            text-align:center;
            color:black;
            width:10%;
            margin-top:6%;
            margin-bottom:3%;
            height:90%;
            float:left;
            padding:0;
            position: fixed;
            display:table-cell;
            vertical-align:center;
            horiz-align: center;
        }
        #section {
            float:left;
            margin-left:10%;
            width:89%;
            margin-top:5%;
            height:95%;
            white-space: normal;
            padding:10px;
        }
        #footer {
            background-color:darkred;
            color:gold;
            clear:both;
            width:100%;
            text-align:center;
            padding:5px;
        }
        #pop {
            border: #000;
        }
        #pop2 {
            border: #000;
        }
        button {
            padding: 0;
            border: none 0;
            font: inherit;
            color: inherit;
            background-color: transparent;
            cursor: pointer;
            top:50%;
            left:50%;
        }
    </style>
    <script src="echarts.min.js"></script>
</head>

<body>

<div id="header">
    <h1 style="color:white; position: relative; top: -10px">Google Play</h1>
</div>
<div class="main">
    <div id="buttons">
        <p style='font-size:18px'><button onclick="goToRd();">Raw Data</button></p>
        <p style='font-size:14px'>Filter</p>
        <form  method="POST">
            <input class="form-control" placeholder="by appname" type="text" name="appname"/>
            <input class="form-control" placeholder="by category" type="text" name="category"/>
            <p style='font-size:12px'>#reviews</p>
            <input class="form-control" placeholder="more than" type="text" name="rew_more"/>
            <input class="form-control" placeholder="less than" type="text" name="rew_less"/>
            <p style='font-size:12px'>rating</p>
            <input class="form-control" placeholder="more than" type="text" name="rat_more"/>
            <input class="form-control" placeholder="less than" type="text" name="rat_less"/>
            <p/>
            <p style='font-size:14px'>Sort</p>
            <input class="form-control" placeholder="sort by" type="text" name="sort"/>
            <input type='submit' style="display:none">
        </form>
        <p/>
        <p style='font-size:18px'><button onclick="goToAlz();">Description</button></p>
        <p style='font-size:14px'><button onclick="goToCat();">Category Pie</button></p>
        <p style='font-size:14px'><button onclick="goToRat();">Rating Bar</button></p>
        <!-- <p style='font-size:14px'><button onclick="goToSiz();">Size Pie</button></p> -->
        <p style='font-size:14px'><button onclick="goToIst();">installs Bar</button></p>
    </div>

    <div id="section">
        <h2 id="rd">Raw Data</h2>
            <?php echo $_GET['subject']; ?>
            <?php
                $servername = "ec2-54-183-81-147.us-west-1.compute.amazonaws.com";
                $username = "project";
                $password = "project";
                $dbname = "project551";
                $conn=mysqli_connect($servername, $username, $password, $dbname);
                if(!$conn){echo "connection fail";}

                // if (isset($_POST["appname"])&!isset($_POST["category"])){
                //     $appnamek = $_POST["appname"];
                //     $sql = "select * from t_tablename where lower(app) like lower('%$appnamek%')";
                // }else if (isset($_POST["category"])&!isset($_POST["appname"])){
                //     $categoryk = $_POST["category"];
                //     $sql = "select * from t_tablename where lower(category) like lower('%$categoryk%')";
                // }else if (isset($_POST["appname"])&isset($_POST["category"])){
                //     $appnamek = $_POST["appname"];
                //     $categoryk = $_POST["category"];
                //     $sql = "select * from t_tablename where lower(category) like lower('%$categoryk%')
                //     and lower(app) like lower('%$appnamek%')";
                // }else{$sql = "select * from t_tablename";}
                // if (isset($_POST["sort"])){
                //     $sort = $_POST["sort"]; 
                //     $sql = $sql . " order by lower($sort)";}
                $where_patch[] = array();
                if (isset($_POST["appname"])  && !empty($_POST["appname"])){$appnamek = $_POST["appname"];
                    $where_patch[] = "lower(app) like lower('%$appnamek%') ";}
                if (isset($_POST["category"])  && !empty($_POST["category"])){$categoryk = $_POST["category"];
                    $where_patch[] = "lower(category) like lower('%$categoryk%') ";}
                if (isset($_POST["rew_more"])  && !empty($_POST["rew_more"])){$rew_morek = $_POST["rew_more"];
                    $where_patch[] = "reviews >= %$rew_morek%) ";}
                if (isset($_POST["rew_less"])  && !empty($_POST["rew_less"])){$rew_lessk = $_POST["rew_less"];
                    $where_patch[] = "reviews <= %$rew_lessk%) ";}
                if (isset($_POST["rat_more"])  && !empty($_POST["rat_more"])){$rat_morek = $_POST["rat_more"];
                    $where_patch[] = "rating >= %$rat_morek%) ";}
                if (isset($_POST["rat_less"])  && !empty($_POST["rat_less"])){$rat_lessk = $_POST["rat_less"];
                    $where_patch[] = "reviews <= %$rat_lessk%) ";}
                $sql = "select * from t_tablename ";
                
                if ($where_patch & count($where_patch) > 1){
                    $sql = $sql . "where " . $where_patch[1];
                    // $i = 2;
                    $length = count($where_patch);
                    for ($i = 2; $i < $length; $i++) {
                        $sql = $sql . "and " . $where_patch[$i];
                        $i = $i + 1;
                    }
                }
                if (isset($_POST["sort"])  && !empty($_POST["sort"])){$sort = $_POST["sort"]; 
                    $sql = $sql . " order by lower($sort)";}
                echo $sql;
                // $sql = $sql . $sql_app . $sql_cat . $sql_rew_more . $sql_rew_less . $sql_sort;
                // 查一下PHP有没有类似 join 的方法








                $res=mysqli_query($conn, $sql);
                $rows=mysqli_affected_rows($conn); //获取行数
                $colums=mysqli_num_fields($res); //获取列数
                function mysqli_field_name($result, $field_offset)
                {
                    $properties = mysqli_fetch_field_direct($result, $field_offset);
                    return is_object($properties) ? $properties->name : null;
                }            
                echo "<div style='height:450px; width:85%; margin-left:5%; overflow:auto;'>";
                echo "<table class='table table-striped'><tr>";
                for($i=0; $i < $colums; $i++){
                    $field_name=mysqli_field_name($res,$i);
                    echo "<th style='font-size:14px; text-align:center;'>$field_name</th>";
                };
                echo "</tr>";
                // array $appname_a = [] 
                while($row=mysqli_fetch_row($res)){
                    // $app = new App();
                    $appname[] = $row[0];
                    $category[] = $row[1];
                    $rating[] = $row[2];
                    // $app->reviews = $row[3];
                    $size[] = $row[4];
                    $installs[] = $row[5];
                    // $app->type = $row[6];
                    // $app->price = $row[7];
                    // $app->ratingby = $row[8];
                    // $app->genre = $row[9];
                    // $app->updated = $row[10];
                    // $app->version = $row[11];
                    // $app->androidv = $row[12];
                    // $data[] = $app;
                    echo "<tr>";
                    for($i=0; $i<$colums; $i++){
                        echo "<td style='font-size:11px'>$row[$i]</td>";
                    }    
                    echo "</tr>";
                 }
                 echo "</table>";
                $count_category = array_count_values($category);
                $json_category = json_encode($count_category );
                $count_rating = array_count_values($rating);
                $json_rating = json_encode($count_rating);
                $count_size = array_count_values($size);
                $json_size = json_encode($count_size);
                $count_installs = array_count_values($installs);
                $json_installs = json_encode($count_installs);
                echo "</div>";
            ?>
        <h2 id="alz">Analyze</h2>
        <p>
            <p/>
            <div id="cat_pic" style="width:90%;height:800px;"></div>
            <p/>
            <div id="rat_pic" style="width:90%;height:800px;"></div>
            <p/>
            <div id="ist_pic" style="width:90%;height:800px;"></div>
            <!-- <p/>
            <div id="siz_pic" style="width:90%;height:800px;"></div> -->
        </p>
    </div>
</div>
<div id="footer">
    Jingying Yin & Yining Zhang
</div>
</body>


<script type="text/javascript" src="jquery-3.0.0.min.js"></script>
<script type="text/javascript">
    function goToRd() {
        document.querySelector("#rd").scrollIntoView({behavior: "instant", block: "end", inline: "nearest"});
        // ("#rd")[0].scrollIntoView();
    }
    function goToAlz() {
        document.querySelector("#alz").scrollIntoView();
        // ("#alz")[0].scrollIntoView();
    }
    function goToCat() {
        document.querySelector("#cat_pic").scrollIntoView();
        // ("#alz")[0].scrollIntoView();
    }
    function goToRat() {
        document.querySelector("#rat_pic").scrollIntoView();
        // ("#alz")[0].scrollIntoView();
    }
    // function goToSiz() {
    //     document.querySelector("#siz_pic").scrollIntoView();
    //     // ("#alz")[0].scrollIntoView();
    // }
    function goToIst() {
        document.querySelector("#ist_pic").scrollIntoView();
        // ("#alz")[0].scrollIntoView();
    }
</script>

<script type="text/javascript" src='echarts.js'></script>

<script type="text/javascript">
    // console.log("123");
    var count_category = <?php echo $json_category; ?>
    
    var cate_name = new Array();  
    var category_data = new Array();  
    var x;
    for (x in Object.keys(count_category)) {
        category_data.push({ name: Object.keys(count_category)[x], value: count_category[Object.keys(count_category)[x]]});
        cate_name.push(Object.keys(count_category)[x]);
    }
        
    var cat_pic = echarts.init(document.getElementById('cat_pic'));
    option = {
        title : {
            text: 'Distribution of Category',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: cate_name
        },
        series : [
            {
                name: 'Check Detail',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: category_data,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    cat_pic.setOption(option);
</script>

<script type="text/javascript">
    var count_rating = <?php echo $json_rating; ?>
    // console.log("count_rating");
    var rat_name = new Array();  
    var rating_data = new Array();  
    var x2;
    for (x2 in Object.keys(count_rating)) {
        rating_data.push({ name: Object.keys(count_rating)[x2], value: count_rating[Object.keys(count_rating)[x2]]});
        rat_name.push(Object.keys(count_rating)[x2]);
    }
    var rat_pic = echarts.init(document.getElementById('rat_pic'));
    option2 = {
        title : {
            text: 'Distribution of Rating',
            x:'center'
        },
        xAxis: {
            type: 'category',
            data: rat_name
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: rating_data,
            type: 'bar'
        }]
    };
    rat_pic.setOption(option2);
</script>

<script type="text/javascript">
    var count_install = <?php echo $json_installs; ?>
    // console.log("count_rating");
    var ist_name = new Array();  
    var ist_data = new Array();  
    var x3;
    for (x3 in Object.keys(count_install)) {
        ist_data.push({ name: Object.keys(count_install)[x3], value: count_install[Object.keys(count_install)[x3]]});
        ist_name.push(Object.keys(count_install)[x3]);
    }
    var ist_pic = echarts.init(document.getElementById('ist_pic'));
    option3 = {
        title : {
            text: 'Distribution of Install',
            x:'center'
        },
        xAxis: {
            type: 'category',
            data: ist_name
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data: ist_data,
            type: 'bar'
        }]
    };
    ist_pic.setOption(option3);
</script>

<!-- 
<script type="text/javascript">
    var count_size = <?php echo $json_size; ?>
    // console.log("count_rating");
    var size_name = new Array();  
    var size_data = new Array();  
    var x4;
    for (x4 in Object.keys(count_size)) {
        size_data.push({ name: Object.keys(count_size)[x4], value: count_install[Object.keys(count_size)[x4]]});
        size_name.push(Object.keys(count_size)[x4]);
    }
    var siz_pic = echarts.init(document.getElementById('siz_pic'));
    option4 = {
        title: {
            text: 'Distribution of Size',
            x:'center'
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            data: size_name
            // data.map(function (item) {
            //     return item[0];
            // })
        },
        yAxis: {
            splitLine: {
                show: false
            }
        },
        toolbox: {
            left: 'center',
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        dataZoom: [{
            startValue: min(size_data)
        }, {
            type: 'inside'
        }],
        visualMap: {
            top: 10,
            right: 10,
            pieces: [{
                gt: 0,
                lte: 50,
                color: '#096'
            }, {
                gt: 50,
                lte: 100,
                color: '#ffde33'
            }, {
                gt: 100,
                lte: 150,
                color: '#ff9933'
            }, {
                gt: 150,
                lte: 200,
                color: '#cc0033'
            }, {
                gt: 200,
                lte: 300,
                color: '#660099'
            }, {
                gt: 300,
                color: '#7e0023'
            }],
            outOfRange: {
                color: '#999'
            }
        },
        series: {
            name: 'Distribution of Size',
            type: 'line',
            data: size_data,
            // data.map(function (item) {
            //     return item[1];
            // }),
            markLine: {
                silent: true,
                data: [{
                    yAxis: 50
                }, {
                    yAxis: 100
                }, {
                    yAxis: 150
                }, {
                    yAxis: 200
                }, {
                    yAxis: 300
                }]
            }
        }
    });
    size_pic.setOption(option4);
</script> -->
</html>




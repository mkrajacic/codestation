<?php
    //$command = 'cd ../node_modules/cors-anywhere/lib/ && npm run start';
    //set_time_limit(5);
    //$output = shell_exec('cd ../node_modules/cors-anywhere/lib/');
    //$output = system('cd ../node_modules/cors-anywhere/lib/ && npm run start');

    $output = shell_exec('cd ../node_modules/cors-anywhere/lib/ && npm run start');
    //echo $output . "<br>";

    if(empty($output)) {
        echo "greška pri pripremi za kodiranje<br>";
    }else{
        echo "upišite kod<br>";
    }
?>
<!DOCTYPE html>
<html>

<body>

    <head>
    </head>
    <textarea id="code" rows=8></textarea>
    <button onclick="UserAction()" id="run">Run</button>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    function UserAction() {

        var script = $('#code').val();

        const proxy = "http://localhost:8080/";
        const url = "https://api.jdoodle.com/v1/execute/";

        var data = {
            clientId: "a64d2cacf27e12f7a9ae76c999c30cef",
            clientSecret: "7bbcc5f655c6c998a0156223713f402f10eeda6adcbfb3e6077d6dd4faa6952d",
            language: "python3",
            script: script,
            versionIndex: "3"
        };

        $.ajax({
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            'type': 'POST',
            'url': proxy + url,
            'data': JSON.stringify(data),
            'dataType': 'json',
            success: function(e) {
                document.write("Rezultat koda: " + e.output + "<br>");
                document.write("Status: " + e.statusCode + "<br>");
                document.write("Memorije iskorišteno: " + e.memory + "<br>");
                document.write("CPU vrijeme: " + e.cpuTime + "<br>");
            },
            error: function(e) {
                document.write("Greška: " + e.memory + "<br>");
                document.write("Kod greške: " + e.statusCode + "<br>");
            }
        });
    }
</script>

</html>
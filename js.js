$(document).ready(function() {

    $(document).on('click', '#button', function () {
        var cost_min = $('#u3_input').val();                // значение минимальной цены
        var cost_max = $('#u5_input').val();                // значение максимальной цены
        var type_cost = $('#u1_input').val();    // выбор типа цены
        var items = $('#u9_input').val();
        var more_or_less = $("#u7_input").val();

        var data = {
            cost_min: cost_min,
            cost_max: cost_max,
            type_cost: type_cost,
            items: items,
            more_or_less: more_or_less
        };

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        success: function(response){
            $('#table').html(" ")
            var res = JSON.parse(response);
            var table = document.querySelector('#table');
            filrtTable(table, res);

            function filrtTable(table, res){
                for (var i=0; i < res.length; i++){
                    var tr = document.createElement('tr');

                    for (var prop in res[i]){
                        var td = document.createElement('td');

                        td.innerHTML = res[i][prop];
                        tr.appendChild(td);
                    }
                    table.appendChild(tr);
                }
            }

        }
    });

    })
});

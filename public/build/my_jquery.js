log("Bonjours tous les mondes !")

function log($mes){
    console.log($mes)
}

// var $post_families = $('#families_to_questions_families');
// var $token = $('#families_to_questions_token');

// $post_families.change(function() {
//     console.log("Bonjours " + $token.val() );

//     var $form = $(this).closest('form');
//     var data = {};
//     data[$token.attr('name')] = $token.val();
//     data[$post_families.attr('name')] = $post_families.val();

//     $.post($form.attr('action'), data).then(function(response) {
//         $('#question_competences').replaceWith(
//             $(response).find("#question_competences")
//         )
//     })
// })


var $post_families = $('#families_to_questions_families');
$hide = $('#question-title, #question-texteComplementaire, #question-autreTexte, #question-motif, #question-types, #question-images');
$hide.hide();
$('#question_submit').attr('disabled', true);
 //GET SERVICE INTO DIRECTION
//GET SERVICE INTO DIRECTION
$post_families.change(function () {
    var url = 'getCompetence/' + $post_families.val();
    $.getJSON(url, function (data) {
        var items = '';
        $('#question_competences').empty();
        if (data == "") {
            console.log("data is null");
            $hide.hide();
            $('#question_submit').attr('disabled', true);
            items += '<option value="'+0+'">Aucune Competence dans cette Articles </option>';
        }
        if (data != "") {
            console.log("data is " + data);
            $.each(data, function (i, competence) {
                items += '<option value="' + competence.id + '"> ' + competence.title + ' </option>';
            });
            $hide.show();
            $('#question_submit').attr('disabled', false);
        }

        $('#question_competences').html(items);
    });

});


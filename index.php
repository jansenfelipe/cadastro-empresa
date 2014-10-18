<?php
require 'vendor/autoload.php';

use JansenFelipe\CnpjGratis\CnpjGratis as CnpjGratis;

$params = CnpjGratis::getParams();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">        
        <title>Teste CNPJ</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    </head>

    <body>

        <!-- Modal -->
        <div class="modal fade" id="captchaModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Captcha</h4>
                    </div>
                    <div class="modal-body">
                        <img src="<?php echo $params['captchaBase64'] ?>" /><br /><br />
                        <input type="text" class="form-control" id="captcha" placeholder="Informe os caracteres da imagem acima" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="consultarCNPJ">Consultar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container -->
        <div class="container">
            <div class="page-header">
                <h1>Cadastro de empresa</h1>
            </div>

            <form role="form">
                <div class="form-group">
                    <label>Informe o CNPJ:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cnpj" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" data-toggle="modal" data-target="#captchaModal" type="button">Consultar</button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Razão social:</label>
                    <input type="text" class="form-control" id="razao_social">
                </div>

                <div class="form-group">
                    <label>Nome fantasia:</label>
                    <input type="text" class="form-control" id="nome_fantasia">
                </div>

                <div class="form-group">
                    <label>Fundação:</label>
                    <input type="text" class="form-control" id="fundacao">
                </div>

                <div class="form-group">
                    <label>Endereço:</label>
                    <input type="text" class="form-control" id="endereco">
                </div>              
            </form>

        </div><!-- /.container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <script>
            $(function() {
                $("#consultarCNPJ").click(function() {
                    var btn = $(this);
                    var old = btn.html();

                    var param = {
                        cnpj: $("#cnpj").val(),
                        captcha: $("#captcha").val(),
                        viewstate: '<?php echo $params['viewstate'] ?>',
                        cookie: '<?php echo $params['cookie'] ?>'
                    };

                    btn.html('Aguarde! Consultando..');

                    $.post("consulta.php", param, function(json) {

                        if (json.code === 0) {

                            $('#razao_social').val(json.razao_social);
                            $('#nome_fantasia').val(json.nome_fantasia);
                            $('#fundacao').val(json.data_abertura);

                            var endereco = json.logradouro + ' ';
                            endereco += json.numero + ' ';
                            endereco += json.complemento + ' ';
                            endereco += '- ' + json.bairro + ' ';
                            endereco += '- Cep:' + json.cep + ' ';
                            endereco += '- ' + json.cidade + '/' + json.uf;

                            $('#endereco').val(endereco);
                        } else
                            alert(json.message);

                        btn.html(old);
                        $('#captchaModal').modal('hide');

                    }, "json");

                });
            });
        </script>
    </body>
</html>

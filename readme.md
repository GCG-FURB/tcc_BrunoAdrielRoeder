# Instruções #

Para rodar o projeto, desenvolvido em PHP, deve-se ter configurado algum servidor web, como por exemplo Apache ou Nginx, e possuir o PHP instalado (testei na versão 5.4 e 5.6); além de um servidor MYSQL (MariaDB v. 5.5.34-MariaDB). Ou caso utilize SO Windows, instalar um XAMPP ([Link Download](https://www.apachefriends.org/pt_br/index.html)) já possibilita o ambiente configurado.

Após configurado o ambiente:

* Importar a Base de Dados, disponibilizado nesse repositório
* No arquivo includes/conectar.php deve-se configurar os dados deacesso ao banco de dados, sendo o primeiro parâmetro o host, o segundo o nome do banco de dados, o terceiro o usuário do Banco e por último a senha do Banco. Exemplo: new Conexao("localhost", "inversos", "root", "123456");

## InVersos de Teste ##

Há um ambiente com as alterações do portal disponível em [http://inversos.brunoroeder.com.br/](http://inversos.brunoroeder.com.br/)
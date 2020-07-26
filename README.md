<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Sobre Laravel Project Api

Integrado a biblioteca JWT
Com auto refresh token configurado.

Integrado com a biblioteca Laravel Enum
Para utilização de tipo inseguros convertidos
para tipos seguros de dados

- [tymon/jwt](https://github.com/tymondesigns/jwt-auth).
- [laravel-enum](https://github.com/BenSampo/laravel-enum).

## Uma Classe ApiResponse Configurada

A Classe ApiResponse já está configurada pra retornar uma resposta json padrão 
RestFull com http Status Code, data, errors,message

## Tratamento de erros para validações com FormRequest

Os error emitidos pelo Laravel nas validações utilizando FormRequest são 
retornados para o cliente e os valores enviados também. Seguindo a seguinte estrutura:

{
    errors:[
        {
           [ key:value]
        }
    ],
    old:[
        {
            [key: value]
        }
    ]
}


## Código de conduta

Para garantir que a comunidade Laravel seja acolhedora para todos, revise e respeite os [Código de conduta](https://laravel.com/docs/contributions#code-of-conduct).

## Vulnerabilidades de segurança

Se você descobrir uma vulnerabilidade de segurança no Laravel, envie um e-mail para Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). Todas as vulnerabilidades de segurança serão prontamente abordadas.

Se você descobrir uma vulnerabilidade de segurança nas configurações realizadas nesse projeto, envie um e-mail para Rangel Dheo via [rangeldheolaravel@gmail.com](rangeldheolaravel@gmail.com). Todas as vulnerabilidades de segurança serão prontamente abordadas.

## Licença

A estrutura do Laravel é um software de código aberto licenciado sob o [MIT license](https://opensource.org/licenses/MIT).

# Umbler API
Essa API **não tem relação oficial** nenhum com os serviços da empresa [Umbler](https://umbler.com/). A atual classe foi criada baseada na documentação oficial do projeto.

**Em testes ainda.**

Tudo está disposto em apenas um arquivo.

Link oficial: https://api.umbler.com/docs/index.html

## Instalação
Sem definição por enquanto. (basta baixar)

### Colaborações
Fique à vontade 😂

## Features
- [X] Autenticação
- [X] Email
- [ ] Registro de Domínio
- [ ] Domínio
- [ ] Contas de FTP
- [ ] Usuários
- [ ] Sites
- [ ] DNS

## Exemplos
### E-mail (disponível por enquanto)

```php
/**
 * Start umbler API
 */
$umblerApi = new UmblerApi\UmblerApi;

/**
 * Define your credentials and domain
 */
$umblerApi->debug = true; # true || false
$umblerApi->setCredentials('userId', 'apiKey'); # check doc
$umblerApi->setDomain('mydomain.com'); # domain
```

Example to get e-mails based on ```$umblerApi->setDomain(mydomain.com)```
```php
$umblerApi->getEmailAccounts();
```

Example to create e-mail account based on ```$umblerApi->setDomain(mydomain.com)```
```php
$umblerApi->createEmailAccount([
  "emailAccount" => "john.doe@mydomain.com",
  "aliases" => ["jd@mydomain.com"],
  "fullName" => "John Doe",
  "password" => 'v3ryS@FEp4$$w0rd',
  "emailType" => "umblermailondemand_5gb"
]);
```

Example to delete e-mail account based on ```$umblerApi->setDomain(mydomain.com)```
```php
$umblerApi->deleteEmailAccount('john.doe@mydomain.com');
```

### Contato
E-mail: julio@ametizze.com.br

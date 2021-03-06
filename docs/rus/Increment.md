Increment через открытый индекс
------------
Команда Increment является модифицирующей БД командой и может выполнятся только через пишущий сокет.

Открываем индекс с колонками `'key', 'num'` и увеличиваем значение колонки `key` на 0, колонки `num` на 3, где `key` = 106.

```php
$writer = new \HS\Writer('localhost', 9999);

$indexId = $writer->getIndexId(
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('key', 'num')
);
$incrementQuery = $writer->incrementByIndex($indexId, '=', array(106), array(0, 3));
```
Если вы полностью уверены в работоспособности вашей команды, вы можете ее просто отослать серверу и не читать ответ, тем самым сэкономить время и память:
```php
$writer->sendQueries();
```
Если вы хотите проверить, что команда выполнена удачно:
```php
$writer->getResultList();
if($incrementQuery->getResult()->isSuccessfully()){
    // запрос удачно обработан
}
```

Другой способ выполнить запрос:
```php
$incrementQuery->execute(); // отправлен запрос + получен ответ на этот запрос + все, что было в очереди на отправку
$incrementResult = $incrementQuery->getResult();
```

Increment с открытием индекса
------------
Данная команда проверит есть ли нужный индекс, если его нет - сначала откроет, а затем выполнит `Increment`.

```php
$incrementQuery = $writer->increment(
    array('key', 'num'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    '=',
    array(106),
    array(0, 5)
);

$writer->getResultList();
```

Increment c помощью QueryBuilder
------------
При инициализации указываем какие колонки на сколько будут увеличены. 

Если указано просто значение, то оно будет увеличено на 1.

Через 'Where' указываем условия отсеивания.
```php
$incrementQueryBuilder = QueryBuilder::increment(array('key' => 0, 'num'))
->fromDataBase($this->getDatabase())
->fromTable($this->getTableName())
->where(Comparison::EQUAL, array('key' => 104));

$incrementQuery = $writer->addQueryBuilder($incrementQueryBuilder);
$writer->getResultList();
```
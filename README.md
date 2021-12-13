# Deprecated
The goal of this package was to simplify the job of adding custom slugs to models, and taking care of things like model binding. However, we ultimately decided that adding a `slug` column directly to our migrations worked better for our use case, and have therefore decided to archive this repository.

For those of you wondering, here's how we usually set up slugs:

``` php
$table->string('slug')->unique();
```

Setting `unique` also indexes the column, so lookups by the slug can avoid a full table scan.

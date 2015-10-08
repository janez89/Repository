# Repository Pattern For Eloquent

Repository Pattern for Laravel Eloquent ORM.
Not need Laravel for use.

## Install

With Composer:
`composer install janez89/repository`

## Usage

- Common Usage
- Laravel 5
- Lumen
- Without Framework


## Common Usage

Sample `Author` model:

```php
<?php

// Author Model
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';
	
	// not necessary ...
    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'int'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
```

Sample `AuthorRepository`:
```php
<?php
// Author Repository
namespace App\Repositories;

use Janez89\Repository\AbstractEloquentRepository;
use App\Models\Author;

class AuthorRepository extends AbstractEloquentRepository
{
    public function getModelClass()
    {
        return Author::class;
    }
}
```

Using the Repository (with API example):

```php
<?php
// ...
use App\Repositories\AuthorRepository;

class AuthorController extends Controller
{
	protected $repository;
	public function __construct(AuthorRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index(Request $request)
	{
		return $this->repository->paginate($request->get('page'));
	}

	public function show($id)
	{
		return $this->repository->find($id);
		// if model not found then thrown ModelNotFoundException
	}
	
	public function store(Request $request)
	{
		// validation here ...
		return $this->repository->create($request->all());
	}

	public function update(Request $request, $id)
	{
		// validation here ...
		
		$model = $this->repository->find($id);
		$model->fill($request->all());
		$model->save();
		// OR
		$this->repository->save($model);
		return $model;
		
		// Custom soulution
		$this->repository->update($request->all(), $id);
		return $this->repository->find($id);
	}
	
	public function destroy($id)
	{
		$this->repository->delete($id);
	}
}
```

### Helper Traits:

####Transactional Helper:
```php
<?php
// Author Repository
namespace App\Repositories;

use Janez89\Repository\AbstractEloquentRepository;
use Janez89\Repository\Traits\EloquentTransactional;
use App\Models\Author;

class AuthorRepository extends AbstractEloquentRepository
{
	use EloquentTransactional;  // <-- here

    public function getModelClass()
    {
        return Author::class;
    }
}
```

Transaction example:
```php
// ...
$repository = new AuthorRepository();

$result = $repository->transaction(function () use ($repository, $request) {
	return $repository->create($request->all());
});

// OR
try {
	$repository->beginTransaction();
	// operations ...
	$repository->commit();
} catch (\Exception $e) {
	$repository->rollback();
}
```

####DataTable Helper

The DataTable helper provide data for  http://datatables.net/

```php
<?php
// Author Repository
namespace App\Repositories;

use Janez89\Repository\AbstractEloquentRepository;
use Janez89\Repository\Traits\DataTables;
use App\Models\Author;

class AuthorRepository extends AbstractEloquentRepository
{
	use DataTables;  // <-- here

    public function getModelClass()
    {
        return Author::class;
    }
}
```

DataTable Example:
```php
<?php
// ...
use App\Repositories\AuthorRepository;

class AuthorController extends Controller
{
	protected $repository;
	public function __construct(AuthorRepository $repository)
	{
		$this->repository = $repository;
	}
	
	public function index()
	{
		return view('author.index');
	}

	public function getData(Request $request)
	{
		return $this->repository->dataTable($request);
	}
	// ...
}
```
DataTable View example:
```html
@extends("layouts.main")
@section('content')
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "/authors/data"
    } );
} );
</script>
@endsection
```


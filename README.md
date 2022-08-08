# Symfony Application using Custom Route Loader

Sample of using a custom controller route loader from a SQLite database in a Symfony application.

## Install

```bash
docker-compose up -d
```

```bash
docker-compose exec symfony-web-application make install uid=$(id -u)
```

## Developing

First we need a controller:
```php
class ProductController extends AbstractController
{
    /**
     * Return single product
     */
    #[Route('/products/{uuid}', 'products_item', requirements: ['uuid' => '.+'], methods: ['GET'])]
    public function item(string $uuid): JsonResponse
    {
        return $this->json(['ok']);
    }
}
```

It is required to store the route in the SQLite database, therefore, to store the data, we will develop the `Route` entity, and also create the migration and fixture.  
Remove the route settings from the existing controller.

## Route entity
Will store basic route settings like path, name, methods, etc.  

```php
#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $requirements = [];

    #[ORM\Column]
    private array $methods = [];

    #[ORM\Column]
    private array $defaults = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function setDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }
}
```

## Migration
Automatically generated migration for SQLite database.  

```php
final class Version20220808073118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, requirements CLOB NOT NULL --(DC2Type:json)
        , methods CLOB NOT NULL --(DC2Type:json)
        , defaults CLOB NOT NULL --(DC2Type:json)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE route');
    }
}
```

## Fixture
Let's create a fixture to populate the `Route` table with data.  

```php
class RouteFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $route = new Route();
        $route->setPath('/products/{uuid}');
        $route->setName('products_item');
        $route->setRequirements(['uuid' => '.+']);
        $route->setMethods(['GET']);
        $route->setDefaults(['_controller' => ProductController::class.'::item']);

        $manager->persist($route);
        $manager->flush();
    }
}
```

## Custom Route Loader
The loader loads the routes from the database.  

```php
/**
 * Loading routes from database
 */
class DatabaseLoader extends Loader
{
    public function __construct(private readonly RouteRepository $routeRepository, string $env = null)
    {
        parent::__construct($env);
    }

    public function load(mixed $resource, string $type = null)
    {
        $routeCollection = new RouteCollection();
        
        foreach ($this->routeRepository->findAll() as $route) {
            $routeCollection->add(
                $route->getName(),
                new Route(
                    $route->getPath(),
                    defaults: $route->getDefaults(),
                    requirements: $route->getRequirements(),
                    methods: $route->getMethods()
                )
            );
        }
        
        return $routeCollection;
    }

    public function supports($resource, string $type = null)
    {
        return $type === 'db';
    }
}
```

## Loader Configuration
```yaml
# /config/services.yaml
services:
    #...
    App\Routing\DatabaseLoader:
        tags: [routing.loader]
```

## Launch Loader
The loader will be executed when the cache is cleared.  

```bash
symfony console cache:clear
```

## Testing

Request
```bash
curl http://127.0.0.1:8000/products/1abe8109-abd5-4ebf-a0fe-71568408188d
```

Response
```bash
["ok"]
```

## Result
Now the routes for the controllers are successfully loaded from the database.  

## Resources
Symfony documentation: [How to Create a custom Route Loader](https://symfony.com/doc/current/routing/custom_route_loader.html)

# Mini LMS

Plataforma mínima de cursos em **PHP 8.3**, **CodeIgniter 4** e **MySQL**. O foco é backend: regras de matrícula, progresso por aula e API REST. A interface web existe só para experimentar o fluxo.

O domínio se parece com o de um LMS real: curso tem ciclo de vida, aluno só se matricula no que está publicado, e o curso só fecha quando todas as aulas foram concluídas.

## Regras de negócio

- Curso nasce como `rascunho`. Só entra no catálogo depois de `publicado`, e precisa ter pelo menos uma aula.
- `GET /api/cursos` devolve somente cursos publicados.
- Matrícula (`POST /api/matriculas`) exige curso publicado, com aulas, e não pode repetir o mesmo aluno.
- Curso `encerrado` bloqueia matrícula nova, mas quem já está matriculado ainda pode concluir aulas.
- `POST /api/aulas/{id}/concluir` exige matrícula. Se a aula já foi concluída, a API é idempotente.
- O percentual é `aulas_concluidas / total_aulas`. Em 100%, a matrícula muda para `concluido`.

As regras ficam em `app/Domain` e `app/Services`, não nos controllers.

## API

| Método | Rota | Quem |
| --- | --- | --- |
| POST | `/api/login` | Público |
| GET | `/api/cursos` | Público |
| GET | `/api/cursos/{id}` | Público (só publicado) |
| POST | `/api/matriculas` | Aluno autenticado |
| GET | `/api/matriculas` | Aluno autenticado |
| POST | `/api/aulas/{id}/concluir` | Aluno autenticado |
| POST | `/api/cursos` | Admin |
| POST | `/api/cursos/{id}/aulas` | Admin |
| POST | `/api/cursos/{id}/publicar` | Admin |
| POST | `/api/cursos/{id}/encerrar` | Admin |

```bash
curl -X POST http://localhost:8081/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"aluno@lms.local\",\"password\":\"Aluno@123\"}"

curl http://localhost:8081/api/cursos

curl -X POST http://localhost:8081/api/matriculas ^
  -H "Authorization: Bearer SEU_TOKEN" ^
  -H "Content-Type: application/json" ^
  -d "{\"course_id\":2}"

curl -X POST http://localhost:8081/api/aulas/4/concluir ^
  -H "Authorization: Bearer SEU_TOKEN"
```

## Stack

| Camada | Tecnologia |
| --- | --- |
| Backend | PHP 8.2+ + CodeIgniter 4 |
| Banco | MySQL 8 |
| API | REST + JSON |
| Testes | PHPUnit |
| Infra | Docker + Docker Compose |

## Como rodar

O MySQL deste projeto usa a porta **3307** para não conflitar com outros containers.

```bash
git clone https://github.com/joselunar/mini-lms.git
cd mini-lms
copy .env.example .env
docker compose up -d mysql
composer install
php spark key:generate
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve --host localhost --port 8081
```

Interface: [http://localhost:8081](http://localhost:8081)

| Perfil | E-mail | Senha |
| --- | --- | --- |
| Aluno | `aluno@lms.local` | `Aluno@123` |
| Admin | `admin@lms.local` | `Admin@123` |

## Docker

`docker-compose.yml` sobe MySQL 8 e um serviço `app` em PHP 8.3. O app escuta em `8081`.

## Testes

```bash
vendor/bin/phpunit --filter "CourseStatusTest|ProgressCalculatorTest"
```

Cobre o ciclo de vida do curso e o cálculo de progresso, que são o coração do domínio.

## Licença

MIT. Veja [LICENSE](LICENSE).

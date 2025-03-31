# diff-comp

[![PHP CI](https://github.com/behindthep/difference-comparator/actions/workflows/workflow.yml/badge.svg)](https://github.com/behindthep/difference-comparator/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/063f9edf4519fcaa134c/maintainability)](https://codeclimate.com/github/behindthep/difference-comparator/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/063f9edf4519fcaa134c/test_coverage)](https://codeclimate.com/github/behindthep/difference-comparator/test_coverage)

### Косольная утилита для определеления разницы между двумя структурами данных.

* Поддержка входных форматов yaml и json
* Генерация отчета в виде plain text, stylish и json

#### Требования:
- php >= 8

### Установка:
```bash
git clone git@github.com:behindthep/difference-comparator.git
make install
```

#### Пример вывода json файла в формате stylish:

```bash
bin/gendiff --format stylish tests/fixtures/file1.json tests/fixtures/file2.json
```

[![asciicast](https://asciinema.org/a/yLMdpouIafxzQNlCYR2dUt4FR.svg)](https://asciinema.org/a/yLMdpouIafxzQNlCYR2dUt4FR)

#### Пример вывода yml файла в формате plain:

```bash
bin/gendiff --format plain tests/fixtures/file1.yml tests/fixtures/file2.yml
```

[![asciicast](https://asciinema.org/a/DmjkbBkD3auZ0L4fZJbstZOAn.svg)](https://asciinema.org/a/DmjkbBkD3auZ0L4fZJbstZOAn)

# Semantic Versioning - Breaking Changes Guide

## 🔢 SemVer Format: MAJOR.MINOR.PATCH

```
v1.1.0
│ │ │
│ │ └── PATCH (0) - Bug fixes, kompatybilne wstecz
│ └──── MINOR (1) - Nowe funkcje, kompatybilne wstecz  
└────── MAJOR (1) - Breaking changes, niekompatybilne API
```

## ❌ Problem z naszym v1.1.0

### Co robimy źle:
```php
// PRZED v1.0.x
$name = $result['show']['name'];  // ✅ Działa

// PO v1.1.0  
$name = $result['show']->name;    // ❌ BŁĄD! Kod przestaje działać
```

### Dlaczego to Breaking Change:
1. **Kod przestaje działać** bez modyfikacji
2. **Zmiana API contract** - inny format danych
3. **Niekompatybilne wstecz** - stary kod nie zadziała

## ✅ Poprawne wersjonowanie

### Opcja 1: MAJOR bump (ZALECANE)
```bash
v1.0.2 → v2.0.0  # Breaking change = nowa major wersja
```

### Opcja 2: Zostać przy MINOR (CONTROVERSIAL)
```bash
v1.0.2 → v1.1.0  # Niektórzy tak robią, ale nie jest to standard
```

## 📖 Oficjalne zasady SemVer

### MAJOR (x.0.0) - Zwiększ gdy:
- ❌ Breaking changes w API
- ❌ Usunięcie funkcji
- ❌ Zmiana formatu danych
- ❌ Niekompatybilne zmiany

### MINOR (x.y.0) - Zwiększ gdy:
- ✅ Nowe funkcje
- ✅ Nowe opcjonalne parametry
- ✅ Kompatybilne wstecz zmiany
- ✅ Deprecation warnings (bez usuwania)

### PATCH (x.y.z) - Zwiększ gdy:
- 🐛 Bug fixes
- 📚 Dokumentacja
- 🔧 Internal refactoring
- 🚀 Performance improvements

## 🎯 Nasz przypadek - Analiza

### Co się zmieniło:
```php
// PRZED
public function searchShows(string $query): array
{
    return [
        ['score' => 0.99, 'show' => ['name' => 'Breaking Bad']]
    ];
}

// PO  
public function searchShows(string $query): array
{
    return [
        ['score' => 0.99, 'show' => Show object]  // Show->name
    ];
}
```

### To jest Breaking Change bo:
- ✅ **Sygnatura metody** - ta sama (`array`)
- ❌ **Format danych** - inny (array vs object)
- ❌ **Kod użytkownika** - przestaje działać
- ❌ **API contract** - zmieniony

## 🔄 Poprawne rozwiązanie

### Opcja A: MAJOR bump (ZALECANE)
```bash
git tag v2.0.0  # Breaking change = major version
```

### Opcja B: Backward compatibility
```php
public function searchShows(string $query, bool $asObjects = false): array
{
    // Domyślnie stary format, nowy tylko gdy requested
    if ($asObjects) {
        return $this->searchShowsAsObjects($query);
    }
    return $this->searchShowsAsArrays($query);  // Stary format
}
```

## 📊 Porównanie podejść

| Wersja | Typ | Breaking? | Kompatybilność |
|--------|-----|-----------|----------------|
| `v1.1.0` | MINOR | ✅ TAK | ❌ Niekompatybilne |
| `v2.0.0` | MAJOR | ✅ TAK | ❌ Niekompatybilne (ale poprawne) |
| `v1.0.3` | PATCH | ❌ NIE | ✅ Kompatybilne |

## 🎯 Wniosek

**Breaking changes powinny zmienić pierwszą wartość wersji (MAJOR), nie drugą (MINOR).**

**Poprawne wersjonowanie:** `v1.0.2 → v2.0.0` 🎯

---

*Dokument wygenerowany automatycznie - 2025-10-13*

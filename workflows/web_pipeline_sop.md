# IA Sport — Pipeline de Creacion y Despliegue Web a Escala

> Modelo operativo para produccion masiva de landing pages via Telegram

---

## 1. Resumen Ejecutivo

**Objetivo:** Automatizar la creacion, despliegue y apuntamiento de sitios web a escala, desde la solicitud del cliente hasta el lanzamiento en produccion con dominio propio.

**Meta:** Producir +100 sitios web diarios a traves de un bot de Telegram, utilizando IA para generacion de codigo y un pipeline de despliegue automatizado.

| Parametro | Detalle |
|---|---|
| Objetivo | Automatizar creacion y despliegue de sitios web desde Telegram |
| Meta de produccion | +100 webs diarias en produccion |
| Stack principal | Claude AI + Telegram Bot + GitHub + Cloudways + Webhook Auto-deploy |
| Tiempo por sitio (manual) | 1.5 a 2 horas |
| Tiempo por sitio (automatizado) | 3 a 5 minutos |

---

## 2. Proceso Actual (Manual)

Documentacion del proceso paso a paso realizado con el caso real de iasport.io, que sirve como modelo base para la automatizacion.

### 2.1 Recepcion del Pedido

El cliente proporciona la informacion inicial:

- URL de referencia de diseno (ej: coderabbit.ai — tema dark premium)
- URL del sitio actual (ej: iasport.io — construido en FlutterFlow)
- Contenido y textos del negocio
- Dominio donde se publicara

**Tiempo estimado:** 5 minutos

### 2.2 Analisis y Captura de Referencia

Se analizan ambos sitios para extraer elementos clave:

- Paleta de colores, tipografia y estilo visual de la referencia
- Estructura de secciones y flujo de navegacion
- Contenido existente del sitio actual para reutilizar
- Puntos de mejora identificados en el diseno original

**Tiempo estimado:** 10-15 minutos

### 2.3 Desarrollo del Sitio

Se crea un archivo HTML/CSS/JS single-page con las siguientes caracteristicas:

- Diseno responsive (mobile, tablet, desktop)
- Tema dark premium inspirado en la referencia
- Secciones: Hero, Demo mockup, Stats, Features, How it works, Testimonials, Integrations, CTA, Footer
- Animaciones: reveal on scroll, contadores animados, chat mockup interactivo
- SEO basico: meta tags, semantica HTML5, Open Graph

**Tiempo estimado:** 30-45 minutos

### 2.4 Revision y Ajustes

El sitio se previsualiza localmente y se itera con el cliente:

- Preview en localhost con servidor Python (`python3 -m http.server 8765`)
- Ajustes de colores, legibilidad y contraste
- Correccion de contenido y textos
- Verificacion responsive en multiples breakpoints

**Tiempo estimado:** 15-30 minutos

### 2.5 Versionamiento

- Inicializacion del repositorio Git
- Commit con mensaje descriptivo
- Push a GitHub (solo bajo aprobacion explicita del cliente)

**Tiempo estimado:** 2-3 minutos

### 2.6 Despliegue a Servidor (Automatizado)

Flujo automatico GitHub → Cloudways via webhook:

1. `git push origin main` desde local
2. GitHub dispara webhook POST a `deploy.php` en Cloudways
3. `deploy.php` ejecuta `git fetch origin && git checkout -f origin/main`
4. Sitio actualizado automaticamente en produccion
5. Verificacion en URL de Cloudways

**Configuracion inicial (una vez):**
- Cloudways: Deployment via GIT → repo SSH + branch `main` + path `public_html/`
- GitHub: Deploy Key con SSH publica de Cloudways
- GitHub: Webhook apuntando a `https://[app].cloudwaysapps.com/deploy.php` con secret
- `deploy.php` valida firma HMAC SHA-256 y ejecuta git pull

**Tiempo estimado:** 0 minutos (automatico en cada push)

### 2.7 Apuntamiento de Dominio

Configuracion DNS para conectar el dominio al servidor:

1. Investigar registros DNS actuales (`dig`, `whois`, `curl`)
2. Identificar el registrador del dominio (ej: GoDaddy)
3. Comunicar al administrador del dominio con nuevos registros A
4. Eliminar registros A obsoletos (ej: viejos IPs de FlutterFlow/AWS)
5. Crear registro A apuntando a IP del servidor Cloudways

**Propagacion DNS:** 5 minutos a 48 horas

### 2.8 Tiempo Total del Proceso Manual

| Etapa | Tiempo |
|---|---|
| Recepcion del pedido | 5 min |
| Analisis de referencia | 10-15 min |
| Desarrollo del sitio | 30-45 min |
| Revision y ajustes | 15-30 min |
| Versionamiento | 2-3 min |
| Despliegue a servidor | 5-10 min |
| Apuntamiento de dominio | 10-15 min |
| **TOTAL** | **1.5 a 2 horas por sitio** |

---

## 3. Modelo Automatizado via Telegram

### 3.1 Flujo del Usuario

El usuario interactua unicamente a traves de Telegram:

1. Usuario abre el bot de Telegram
2. Envia: "Quiero un sitio web para [mi negocio]"
3. Bot pregunta: referencia de diseno, dominio, contenido, colores
4. Bot confirma el pedido y genera el sitio automaticamente
5. Bot envia URL de preview para aprobacion
6. Usuario aprueba o solicita cambios
7. Bot despliega a produccion y configura DNS
8. Bot notifica al usuario con URL final del sitio live

### 3.2 Arquitectura del Sistema

| Componente | Tecnologia | Funcion |
|---|---|---|
| Telegram Bot | Python / Node.js | Interfaz con usuario, recepcion de pedidos |
| Orquestador | Claude API / Agent SDK | Genera codigo HTML, decisiones de diseno |
| Templates Engine | Biblioteca HTML/CSS | Disenos base para acelerar generacion |
| Repositorio | GitHub | Versionamiento automatico + webhook |
| Hosting | Cloudways (PHP Stack) | Servidor con Varnish cache + auto-deploy via webhook |
| DNS Manager | GoDaddy / Cloudflare | Registro A apuntando a IP de Cloudways |
| CDN | Cloudways Varnish | Cache en servidor |
| Dashboard | Panel web admin | Monitoreo de pedidos y metricas |
| Base de datos | PostgreSQL / Supabase | Registro de clientes y configuraciones |

### 3.3 Flujo Tecnico Detallado

Cada paso que el sistema ejecuta de forma automatica:

| Paso | Accion | Componente | Detalles |
|---|---|---|---|
| 1 | Recibir mensaje y extraer intencion | Telegram Bot | Parsear texto, detectar tipo de solicitud |
| 2 | Analizar sitio de referencia | Claude API | Scraping de URL, extraer paleta/estructura |
| 3 | Generar HTML basado en template + contenido | Claude API | Seleccionar template, inyectar contenido del cliente |
| 4 | Crear repositorio en GitHub | GitHub API | `POST /user/repos`, push del codigo generado |
| 5 | Auto-deploy a Cloudways | Webhook | Push a GitHub dispara deploy.php → git pull automatico |
| 6 | Configurar dominio via DNS | GoDaddy/Cloudflare | Registro A apuntando a IP del servidor Cloudways |
| 7 | Verificar sitio live y SSL activo | Health Check | Curl + verificar status 200 y cert SSL valido |
| 8 | Notificar al usuario con URL final | Telegram Bot | Enviar mensaje con link y screenshot |

### 3.4 Flujo de Iteracion (Cambios)

Cuando el usuario no aprueba el preview:

1. Usuario responde "cambiar colores" o "mover seccion X"
2. Bot interpreta la instruccion via Claude API
3. Se modifica el HTML existente (no regenerar desde cero)
4. Se hace commit + redeploy automatico
5. Bot envia nuevo preview
6. Repetir hasta aprobacion

**Limite de iteraciones sugerido:** 3 rondas incluidas en plan basico.

---

## 4. Infraestructura para 100+ Webs Diarias

### 4.1 Hosting — Cloudways (Configuracion Actual)

| Componente | Detalle |
|---|---|
| Servidor | Cloudways PHP Stack |
| IP | 104.156.244.244 |
| App URL | phpstack-1612861-6411062.cloudwaysapps.com |
| Deploy | Automatico via GitHub Webhook → deploy.php |
| Cache | Varnish (bypass con `?nocache=`) |
| Webroot | public_html/ |
| Git branch | main |

**Auto-deploy configurado:** GitHub webhook POST → `deploy.php` → valida HMAC → `git fetch && git checkout`

Para escalar a +100 sitios, se pueden crear multiples aplicaciones en Cloudways, cada una con su propio webhook.

### 4.2 Estimacion de Costos Mensuales

Basado en 100 webs/dia = 3,000 sitios/mes:

| Recurso | Costo Estimado/Mes |
|---|---|
| Claude API (Sonnet) | $500 - $1,500 |
| Servidor Telegram Bot (VPS) | $20 - $50 |
| Cloudways (hosting) | $100 - $500 |
| GitHub Organization | $0 - $44 |
| DNS APIs (Cloudflare) | $0 - $20 |
| Base de datos (Supabase) | $0 - $25 |
| Dominio del bot + dashboard | $10 - $15 |
| **TOTAL ESTIMADO** | **$530 - $1,674** |

**Costo por sitio:** $0.18 - $0.56 USD

### 4.3 Limitaciones Tecnicas y Soluciones

| Limitacion | Impacto | Solucion |
|---|---|---|
| Rate limits de Claude API | Bottleneck en generacion | Tier de produccion, Batch API para volumen alto |
| Tiempo de generacion (~2-3 min/sitio) | Maximo ~40 sitios secuenciales/dia | Workers paralelos (10 workers = 400+ sitios/dia) |
| Almacenamiento de sitios | Crece ~150MB/mes | Sitios estaticos ~50KB, costo negligible |
| Propagacion DNS lenta | Cliente espera horas | Usar solo Cloudflare (propagacion < 5 min) |
| Personalizacion vs velocidad | Mas custom = mas lento | Biblioteca de 20-30 templates + personalizacion IA |

---

## 5. Stack Tecnologico Recomendado

### 5.1 Para MVP (Semana 1-4)

```
Telegram Bot (Python)
  |
  v
Claude API (Sonnet)  -->  Genera HTML desde template
  |
  v
GitHub API  -->  Crea repo + push automatico
  |
  v
GitHub Webhook  -->  POST a deploy.php en Cloudways (auto-deploy)
  |
  v
DNS (GoDaddy/Cloudflare)  -->  Registro A a IP de Cloudways
  |
  v
Supabase  -->  Registro de cliente + estado del pedido
```

**Dependencias MVP:**
- `python-telegram-bot` — bot framework
- `anthropic` — Claude API SDK
- `PyGithub` — GitHub API wrapper
- `httpx` — requests HTTP para Cloudflare API
- 10-15 templates HTML prediseñados en `/templates/`
- Un solo VPS ($20/mes) para correr el bot

### 5.2 Para Escala (100+/dia)

```
Telegram Bot (Node.js)
  |
  v
Redis + BullMQ  -->  Cola de tareas
  |
  v
Worker Pool (5-10 workers)
  |---> Worker 1: Claude API --> GitHub --> Cloudflare
  |---> Worker 2: Claude API --> GitHub --> Cloudflare
  |---> Worker N: ...
  |
  v
Dashboard (Next.js)  -->  Panel admin, metricas, estado
  |
  v
Supabase / PostgreSQL  -->  DB principal
```

**Componentes adicionales:**
- Node.js con workers concurrentes
- Cola de tareas (BullMQ + Redis)
- Claude API con batch processing
- Auto-provisioning de sitios via API
- Panel admin para monitoreo en tiempo real
- Webhooks para notificaciones de estado
- Load balancer para distribuir generacion

---

## 6. Roadmap de Implementacion

### Fase 1 — MVP (Semana 1-3)

**Objetivo:** Validar que el flujo funciona end-to-end.

- [ ] Crear bot de Telegram basico con flujo conversacional
- [ ] Integrar Claude API para generacion de HTML
- [ ] Desarrollar 5 templates base (dark, light, corporativo, landing, portfolio)
- [ ] Deploy automatico via GitHub webhook a Cloudways
- [ ] Prueba con 5-10 sitios reales
- [ ] Documentar errores y ajustar prompts

**Criterio de exito:** Generar 10 sitios funcionales con aprobacion del cliente.

### Fase 2 — Automatizacion (Semana 4-6)

**Objetivo:** Zero-touch deployment.

- [ ] Auto-deploy a Cloudways via webhook (YA IMPLEMENTADO)
- [ ] Auto-creacion de repos en GitHub
- [ ] Flujo completo sin intervencion humana
- [ ] Dashboard basico de monitoreo
- [ ] Sistema de preview + aprobacion en Telegram
- [ ] Prueba con 20-30 sitios/dia

**Criterio de exito:** Un sitio generado y desplegado sin tocar terminal.

### Fase 3 — Escala (Semana 7-10)

**Objetivo:** 100 sitios/dia de forma estable.

- [ ] Workers paralelos para generacion simultanea
- [ ] DNS automation via Cloudflare API
- [ ] Templates expandidos (20-30 variantes)
- [ ] Sistema de revisiones/iteraciones por chat
- [ ] Monitoring y alertas automaticas (uptime, errores)
- [ ] Rate limiting y proteccion anti-abuso

**Criterio de exito:** 100 sitios/dia durante 5 dias consecutivos sin fallos criticos.

### Fase 4 — Monetizacion (Semana 11+)

**Objetivo:** Revenue positivo y crecimiento organico.

- [ ] Sistema de pagos integrado (Stripe / MercadoPago)
- [ ] Planes: Basico ($29), Premium ($79), Enterprise ($199)
- [ ] Soporte multi-idioma (ES, EN, PT)
- [ ] Analytics integrado por sitio (Plausible/Umami)
- [ ] White-label para agencias
- [ ] Programa de referidos

**Criterio de exito:** 50 clientes de pago recurrente.

---

## 7. Metricas Clave (KPIs)

| Metrica | Objetivo | Frecuencia | Como medir |
|---|---|---|---|
| Tiempo promedio de generacion | < 5 minutos por sitio | Diaria | Timestamp inicio → fin en DB |
| Tasa de aprobacion primer intento | > 70% | Semanal | Aprobaciones / Total generados |
| Sitios desplegados por dia | 100+ | Diaria | Contador en dashboard |
| Costo por sitio generado | < $1 USD | Mensual | Costos totales / Sitios generados |
| Satisfaccion del cliente | > 4.5/5 | Por entrega | Rating en Telegram post-entrega |
| Uptime de sitios desplegados | > 99.9% | Continua | Health checks automaticos |
| Tasa de conversion (trial a pago) | > 15% | Mensual | Pagos / Registros |

---

## 8. Riesgos y Mitigaciones

| Riesgo | Probabilidad | Impacto | Mitigacion |
|---|---|---|---|
| Caida de Claude API | Baja | Alto | Fallback a templates estaticos sin IA |
| Sitios con bugs visuales | Media | Medio | Preview obligatorio antes de deploy |
| Abuso del bot (spam) | Media | Alto | Rate limiting, verificacion de usuario, pagos previos |
| Costos de API descontrolados | Media | Alto | Limites diarios, monitoreo de uso, cache de generaciones |
| Problemas de DNS | Baja | Medio | Usar solo Cloudflare (propagacion rapida) |
| Competencia de mercado | Alta | Medio | Diferenciacion por velocidad y calidad IA |
| Escalabilidad del bot | Media | Alto | Arquitectura de microservicios desde Fase 2 |
| Calidad inconsistente del HTML | Media | Alto | Tests automaticos (Lighthouse, validador W3C) |

---

## 9. Comandos del Bot (Referencia Rapida)

Comandos que el bot de Telegram deberia soportar:

```
/start          - Iniciar conversacion, crear cuenta
/nueva          - Solicitar nueva web
/estado         - Ver estado de pedidos activos
/preview        - Ver preview del sitio en progreso
/aprobar        - Aprobar el preview actual
/cambios        - Solicitar modificaciones
/dominios       - Configurar dominio propio
/plan           - Ver/cambiar plan de suscripcion
/ayuda          - Mostrar comandos disponibles
```

---

## 10. Estructura de Archivos del Proyecto

```
ia-sport-web-factory/
├── bot/                        # Telegram Bot
│   ├── main.py                 # Entry point
│   ├── handlers/               # Command handlers
│   │   ├── start.py
│   │   ├── nueva_web.py
│   │   ├── preview.py
│   │   └── cambios.py
│   ├── services/               # Business logic
│   │   ├── generator.py        # Claude API integration
│   │   ├── deployer.py         # Cloudflare Pages deploy
│   │   ├── dns_manager.py      # DNS configuration
│   │   └── github_service.py   # Repo management
│   └── config.py               # Environment config
├── templates/                  # HTML templates base
│   ├── dark-premium/
│   ├── light-clean/
│   ├── corporate/
│   ├── landing-page/
│   └── portfolio/
├── dashboard/                  # Admin panel (Next.js)
│   ├── pages/
│   └── components/
├── workers/                    # Background workers (Fase 3)
│   ├── generator_worker.py
│   └── deploy_worker.py
├── database/                   # Schema y migrations
│   └── schema.sql
├── .env                        # API keys (NUNCA commitear)
├── docker-compose.yml          # Orquestacion local
└── README.md
```

---

## 11. Variables de Entorno Requeridas

```env
# Telegram
TELEGRAM_BOT_TOKEN=

# Claude AI
ANTHROPIC_API_KEY=

# GitHub
GITHUB_TOKEN=
GITHUB_ORG=

# Cloudflare
CLOUDFLARE_API_TOKEN=
CLOUDFLARE_ACCOUNT_ID=

# Supabase
SUPABASE_URL=
SUPABASE_ANON_KEY=
SUPABASE_SERVICE_KEY=

# Pagos (Fase 4)
STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=
```

---

## 12. Esquema de Base de Datos

```sql
-- Clientes
CREATE TABLE clients (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    telegram_id BIGINT UNIQUE NOT NULL,
    name TEXT,
    email TEXT,
    plan TEXT DEFAULT 'free',
    created_at TIMESTAMPTZ DEFAULT now()
);

-- Pedidos de sitios web
CREATE TABLE orders (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    client_id UUID REFERENCES clients(id),
    status TEXT DEFAULT 'pending',  -- pending, generating, preview, approved, deployed, live
    reference_url TEXT,
    domain TEXT,
    content JSONB,
    template TEXT,
    github_repo TEXT,
    cloudflare_project TEXT,
    preview_url TEXT,
    live_url TEXT,
    iterations INT DEFAULT 0,
    created_at TIMESTAMPTZ DEFAULT now(),
    deployed_at TIMESTAMPTZ
);

-- Log de acciones del pipeline
CREATE TABLE pipeline_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID REFERENCES orders(id),
    step TEXT NOT NULL,       -- generate, deploy, dns, verify
    status TEXT NOT NULL,     -- started, success, error
    details JSONB,
    duration_ms INT,
    created_at TIMESTAMPTZ DEFAULT now()
);
```

---

*Documento generado: Mayo 2026 | IA Sport Web Factory*

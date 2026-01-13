=== Unidades de Atendimento ===
Contributors: marcoscti
Tags: unidades, hospital, upa, saúde, shortcode, cards, filtro
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin para gerenciamento e exibição de Unidades de Atendimento (Hospitais e UPAs) com filtros e layout em cards.

== Description ==

O plugin **Unidades de Atendimento** permite cadastrar, gerenciar e exibir unidades de saúde como **Hospitais** e **UPAs** diretamente pelo painel administrativo do WordPress.

As unidades são exibidas no site por meio de um **shortcode**, em formato de **cards responsivos**, com botões de filtro dinâmico por tipo de atendimento (Hospital, UPA ou Todos), sem recarregar a página.

Ideal para:
- Sites institucionais
- Portais de saúde
- Prefeituras
- Organizações públicas ou privadas

Principais recursos:
- CRUD completo no admin
- Controle de unidades ativas/inativas
- Campos personalizados essenciais
- Shortcode simples
- Filtro por tipo de unidade
- Layout moderno com cor padrão #0094C6

== Installation ==

1. Faça o upload da pasta `unidades-atendimento` para o diretório `/wp-content/plugins/`
   ou envie o arquivo ZIP pelo painel do WordPress.
2. Ative o plugin em **Plugins → Plugins Instalados**.
3. No menu lateral do admin, acesse **Unidades de Atendimento**.
4. Cadastre suas unidades.
5. Utilize o shortcode `[unidades_atendimento]` na página desejada.

== How to Use ==

### Cadastro de Unidade

Acesse:
Painel WordPress → Unidades de Atendimento → Adicionar Nova

Preencha os campos:

- **Título da unidade** (Obrigatório)
  Nome da unidade de atendimento.

- **Descrição** (Obrigatório)
  Informações gerais, serviços, horários etc.

- **Endereço** (Obrigatório)
  Endereço completo da unidade.

- **Telefone** (Opcional)
  Telefone de contato.

- **Link** (Opcional)
  Link para site oficial ou Google Maps.

- **Imagem destacada** (Opcional, recomendado)
  Imagem usada no card da unidade.

- **Tipo** (Obrigatório)
  Selecione:
  - Hospital
  - UPA

- **Ativo** (Obrigatório para exibição)
  Apenas unidades marcadas como "Ativo" aparecem no site.

### Shortcode

Use o shortcode abaixo para exibir as unidades no site:

[unidades_atendimento]

Você pode inserir o shortcode em:

- Bloco **Shortcode** do Gutenberg
- Widget **Shortcode** do Elementor
- Conteúdo de páginas ou posts

== Filters ==

No frontend, o plugin exibe três botões de filtro:

- **Todos** → Exibe todas as unidades ativas
- **Hospital** → Exibe apenas unidades do tipo Hospital
- **UPA** → Exibe apenas unidades do tipo UPA

O filtro é feito via JavaScript, sem recarregar a página.

== Styling ==

- Layout em cards responsivos
- Grid automático
- Cor principal: #0094C6
- Estilos carregados automaticamente pelo shortcode

== Frequently Asked Questions ==

= As unidades não aparecem no site =
Verifique se:
- A unidade está marcada como **Ativo**
- O shortcode foi inserido corretamente
- A unidade possui um **Tipo** definido

= Posso usar mais de uma vez o shortcode? =
Sim. O shortcode pode ser usado em quantas páginas quiser.

= Posso personalizar o layout? =
Sim. Você pode sobrescrever os estilos via CSS no tema.

== Changelog ==

= 1.0.0 =
* Versão inicial
* Cadastro de unidades
* Shortcode com filtros
* Layout em cards

== Upgrade Notice ==

= 1.0.0 =
Versão inicial estável do plugin.

== License ==

This plugin is licensed under the GPL v2 or later.
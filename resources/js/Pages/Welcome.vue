<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, CheckCircle2, Clock, Users, ShieldCheck, Video, FolderTree, Building2 } from 'lucide-vue-next';
</script>

<template>
  <Head title="Daily Contábil – Landing" />
  <div class="min-h-screen bg-[#F8FAFC] text-gray-800">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur border-b border-gray-200">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <img src="/images/daily-logo.png" class="h-8 w-8" />
          <span class="font-semibold text-[#0D3D56]">Daily Contábil</span>
        </div>
        <a href="mailto:contato@dailycontabil.com.br" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-md text-white" style="background-color:#D97706">
          Agendar Demo <ArrowRight class="w-4 h-4" />
        </a>
      </div>
    </header>

    <!-- Hero -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <h1 class="text-4xl lg:text-4xl font-extrabold leading-tight text-[#0D3D56]">Transforme o conhecimento do seu escritório em um ativo permanente.</h1>
        <p class="mt-5 text-lg text-gray-700">Plataforma de gestão de conhecimento e treinamento interno para escritórios de contabilidade. Economize horas do sênior e acelere o onboarding do time.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <a href="mailto:contato@dailycontabil.com.br" class="inline-flex items-center gap-2 px-6 py-3 rounded-md text-white bg-brand-accent">Agendar uma Demonstração <ArrowRight class="w-5 h-5" /></a>
          <Link :href="route('login')" class="inline-flex items-center px-6 py-3 rounded-md border border-gray-300 bg-white">Acessar o App</Link>
        </div>
      </div>
      <div class="w-full overflow-hidden flex justify-center">
        <img src="/images/heroimg.png" alt="Mockup" class="w-[500px] h-auto shadow-sm rounded-2xl" />
      </div>
    </section>

    <!-- Social Proof -->
    <section class="py-10 border-t border-b border-gray-200 bg-brand">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm uppercase tracking-wider text-gray-500">Confiado por escritórios de contabilidade</p>
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-6">
          <div class="h-10 bg-gray-100 rounded" /><div class="h-10 bg-gray-100 rounded" /><div class="h-10 bg-gray-100 rounded" /><div class="h-10 bg-gray-100 rounded" /><div class="h-10 bg-gray-100 rounded" /><div class="h-10 bg-gray-100 rounded" />
        </div>
      </div>
    </section>

    <!-- Problem -->
    <section id="problema" class="py-16 lg:py-24 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-[#0D3D56]">O conhecimento do seu escritório está vazando (e custando caro).</h2>
      <div class="mt-10 grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm"><Clock class="w-6 h-6 text-[#D97706]" /><h3 class="mt-4 font-semibold">Horas de sênior perdidas</h3><p class="mt-2 text-gray-600">Sêniors interrompem o foco para repetir treinamentos básicos.</p></div>
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm"><Users class="w-6 h-6 text-[#D97706]" /><h3 class="mt-4 font-semibold">Treinamento demorado</h3><p class="mt-2 text-gray-600">Onboarding lento, sem trilhas claras e padrão.</p></div>
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm"><ShieldCheck class="w-6 h-6 text-[#D97706]" /><h3 class="mt-4 font-semibold">Conhecimento perdido</h3><p class="mt-2 text-gray-600">Know-how vai embora quando alguém sai.</p></div>
      </div>
    </section>

    <!-- Solution / Features -->
    <section id="solucao" class="py-16 lg:py-24 bg-white border-t border-b border-gray-200">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#0D3D56]">Conheça o Daily Contábil</h2>
        <div class="mt-12 grid lg:grid-cols-3 gap-6">
          <div class="p-6 rounded-lg border bg-white shadow-sm"><div class="flex items-center gap-3"><Video class="w-6 h-6 text-[#D97706]" /><h3 class="font-semibold">Crie tutoriais ricos</h3></div><p class="mt-3 text-gray-600">Vídeos, textos e planilhas em passos claros.</p></div>
          <div class="p-6 rounded-lg border bg-white shadow-sm"><div class="flex items-center gap-3"><FolderTree class="w-6 h-6 text-[#D97706]" /><h3 class="font-semibold">Centralize por categoria</h3></div><p class="mt-3 text-gray-600">Organize por Fiscal, Contábil, Pessoal etc.</p></div>
          <div class="p-6 rounded-lg border bg-white shadow-sm"><div class="flex items-center gap-3"><Building2 class="w-6 h-6 text-[#D97706]" /><h3 class="font-semibold">Conhecimento por cliente</h3></div><p class="mt-3 text-gray-600">Processos específicos por empresa atendida.</p></div>
        </div>
      </div>
    </section>

    <!-- Benefits -->
    <section id="beneficios" class="py-16 lg:py-24 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-lg bg-white border shadow-sm"><h3 class="text-xl font-semibold text-[#0D3D56]">Reduza o onboarding em 50%</h3><p class="mt-2 text-gray-600">Trilhas claras e conteúdo 24/7 aceleram o aprendizado.</p></div>
        <div class="p-6 rounded-lg bg-white border shadow-sm"><h3 class="text-xl font-semibold text-[#0D3D56]">Padronize 100% dos processos</h3><p class="mt-2 text-gray-600">Qualidade consistente, independente de quem executa.</p></div>
        <div class="p-6 rounded-lg bg-white border shadow-sm"><h3 class="text-xl font-semibold text-[#0D3D56]">Preserve o capital intelectual</h3><p class="mt-2 text-gray-600">O conhecimento fica na empresa.</p></div>
      </div>
      <ul class="mt-8 grid md:grid-cols-2 gap-3 text-gray-700">
        <li class="flex items-start gap-2"><CheckCircle2 class="w-5 h-5 text-[#D97706]" /> Implantação assistida</li>
        <li class="flex items-start gap-2"><CheckCircle2 class="w-5 h-5 text-[#D97706]" /> Segurança por perfil</li>
        <li class="flex items-start gap-2"><CheckCircle2 class="w-5 h-5 text-[#D97706]" /> Relatórios de engajamento</li>
        <li class="flex items-start gap-2"><CheckCircle2 class="w-5 h-5 text-[#D97706]" /> Suporte prioritário</li>
      </ul>
    </section>

    <!-- Testimonials -->
    <section id="depoimentos" class="py-16 lg:py-24 bg-white border-t border-b border-gray-200">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#0D3D56]">O que dizem</h2>
        <div class="mt-12 grid md:grid-cols-3 gap-6">
          <figure class="p-6 rounded-lg border bg-white shadow-sm"><blockquote class="text-gray-700">“Economizamos 10 horas/semana em treinamento.”</blockquote><figcaption class="mt-4 text-sm text-gray-500">Diretor, Escritório Alfa</figcaption></figure>
          <figure class="p-6 rounded-lg border bg-white shadow-sm"><blockquote class="text-gray-700">“Onboarding caiu pela metade.”</blockquote><figcaption class="mt-4 text-sm text-gray-500">Sócia, Contabilidade Beta</figcaption></figure>
          <figure class="p-6 rounded-lg border bg-white shadow-sm"><blockquote class="text-gray-700">“Conhecimento padronizado e preservado.”</blockquote><figcaption class="mt-4 text-sm text-gray-500">CEO, Grupo Gama</figcaption></figure>
        </div>
      </div>
    </section>

    <!-- Final CTA -->
    <section class="py-16 lg:py-24 text-center mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-[#0D3D56]">Pronto para otimizar seu escritório?</h2>
      <p class="mt-3 text-gray-700">Agende uma demonstração e veja o Daily Contábil em ação.</p>
      <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="mailto:contato@dailycontabil.com.br" class="inline-flex items-center gap-2 px-6 py-3 rounded-md text-white" style="background-color:#D97706">Agendar uma Demonstração <ArrowRight class="w-5 h-5" /></a>
        <Link :href="route('login')" class="inline-flex items-center px-6 py-3 rounded-md border border-gray-300 bg-white">Acessar o App</Link>
      </div>
    </section>

    <footer class="border-t border-gray-200 py-8 text-center text-sm text-gray-500"> {{ new Date().getFullYear() }} Daily Contábil</footer>
  </div>
</template>

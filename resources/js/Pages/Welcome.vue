<script setup>
import LandingHeader from '@/Layouts/LandingHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, CheckCircle2, Clock, Users, ShieldCheck, Video, FolderTree } from 'lucide-vue-next';

// Testimonials data (static grid)
const testimonials = [
  {
    quote: '“Economizamos 10 horas/semana em treinamento.”',
    author: 'Eduardo Martins',
    role: 'Diretor, Escritório Alfa',
    avatar: '/images/depoimentoUm.jpg',
  },
  {
    quote: '“Onboarding caiu pela metade.”',
    author: 'Marina Lopes',
    role: 'Sócia, Contabilidade Beta',
    avatar: '/images/DepoimentoDois.jpeg',
  },
  {
    quote: '“Conhecimento padronizado e preservado.”',
    author: 'Rafael Soares',
    role: 'CEO, Grupo Gama',
    avatar: '/images/depoimentoTres.webp',
  },
];
</script>

<template>
  <Head title="Daily Contábil – Landing" />
  <div class="min-h-screen text-gray-800 bg-white">
    <!-- Header -->
    <LandingHeader />

    <!-- Hero -->
    <section class="w-full px-4 sm:px-6 lg:px-8 pt-28 pb-16 lg:py-32 relative overflow-hidden">
      <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#FFFBEB] via-white to-white"></div>
      <div class="max-w-7xl grid lg:grid-cols-2 gap-12 items-center mx-auto">
        <div>
          <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Nova plataforma para escritórios</span>
          <h1 class="font-mont text-5xl md:text-4xl font-extrabold tracking-tight mt-4 leading-tight text-[#0D3D56]">
            Transforme o conhecimento do seu escritório em <span class="text-amber-600">um ativo permanente</span>.
          </h1>
          <p class="mt-5 text-lg md:text-xl text-gray-700 max-w-2xl">Plataforma de gestão de conhecimento e treinamento interno para escritórios de contabilidade. Economize horas do sênior e acelere o onboarding do time.</p>
          <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="mailto:contato@dailycontabil.com.br" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-md text-white bg-brand-accent hover:brightness-110 transition">
              Agendar uma Demonstração <ArrowRight class="w-5 h-5" />
            </a>
          </div>
          <ul class="mt-6 flex flex-wrap gap-4 text-sm text-gray-600">
            <li class="flex items-center gap-2"><CheckCircle2 class="w-4 h-4 text-[#D97706]" />Implantação assistida</li>
            <li class="flex items-center gap-2"><CheckCircle2 class="w-4 h-4 text-[#D97706]" />Sem fidelidade</li>
            <li class="flex items-center gap-2"><CheckCircle2 class="w-4 h-4 text-[#D97706]" />Suporte Rápido</li>
          </ul>
        </div>
        <div class="w-full flex justify-center">
          <img src="/images/hero-image.png" alt="Mockup" class="w-[520px] h-auto shadow-xl shadow-amber-100 rounded-2xl" />
        </div>
      </div>
    </section>

    <!-- Social Proof -->
    <section class="py-2 border-gray-200">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm uppercase tracking-wider text-text-primary font-semibold">Confiado por escritórios de contabilidade</p>
        <div class="mt-6 overflow-hidden">
          <div class="logo-marquee flex items-center gap-10 will-change-transform">
            <template v-for="i in 12" :key="i">
              <div class="h-10 w-28 rounded bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-700/70 font-medium">LOGO</div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Problem -->
    <section id="problema" class="py-16 lg:py-24 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="w-4/6 mx-auto font-mont text-3xl md:text-4xl font-extrabold text-[#0D3D56] text-center">O conhecimento do seu escritório está vazando (e custando caro).</h2>
      <div class="mt-10 grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col justify-center items-center"><div><img src="/images/hourslost.png" alt="Horas perdidas" class="w-24 h-24" /></div><h3 class="mt-4 font-semibold">Horas de sênior perdidas</h3><p class="mt-2 text-gray-600">Sêniors interrompem o foco para repetir treinamentos básicos.</p></div>
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col justify-center items-center"><div><img src="/images/slowonboarding3.png" alt="Treinamento demorado" class="w-24 h-24" /></div><h3 class="mt-4 font-semibold">Treinamento demorado</h3><p class="mt-2 text-gray-600">Onboarding lento, sem trilhas claras e padrão.</p></div>
        <div class="p-6 rounded-lg bg-white border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col justify-center items-center"><div><img src="/images/lostknowhow.png" alt="Conhecimento perdido" class="w-24 h-24" /></div><h3 class="mt-4 font-semibold">Conhecimento perdido</h3><p class="mt-2 text-gray-600">Know-how vai embora quando alguém sai.</p></div>
      </div>
    </section>

    <!-- Solution / Features -->
    <section id="solucao" class="py-16 lg:py-24">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 rounded-3xl">
        <h2 class="font-mont text-3xl md:text-4xl font-extrabold text-center text-brand">Conheça o Daily Contábil</h2>
        <div class="mt-12 flex flex-col gap-6">
          <div class="p-6 rounded-3xl flex justify-between items-center text-white bg-brand px-12">
            <div class="container-text w-1/2">
              <div class="flex items-center gap-3"><Video class="w-12 h-12 text-[#D97706]" />
                <h3 class="font-bold font-mont text-white text-3xl">Tutoriais por passos</h3>
              </div>
              <p class="text-xl mt-3 text-white w-3/4">Transforme processos em trilhas com vídeos, PDFs e planilhas. Defina níveis, inclua materiais de apoio e padronize a execução.</p>
            </div>
            <div class="container-image w-1/2 flex justify-end">
              <img src="/images/tutorialSection.png" alt="Mockup" class="w-[520px] h-auto rounded-2xl" />
            </div>
          </div>
          <div class="p-6 rounded-3xl flex justify-between items-center bg-brand px-12 text-white">
            <div class="container-image w-1/2 flex justify-start">
              <img src="/images/categorySection.png" alt="Mockup" class="w-[520px] h-auto rounded-2xl" />
            </div>
            <div class="container-text w-1/2">
              <div class="flex items-center gap-3"><FolderTree class="w-12 h-12 text-[#D97706]" />
                <h3 class="font-bold font-mont text-white text-3xl">Biblioteca e categorias</h3>
              </div>
              <p class="text-xl mt-3 text-white w-3/4">Centralize o conhecimento por área (Fiscal, Contábil, Pessoal). Uploads grandes com armazenamento seguro em nuvem (S3).</p>
            </div>
          </div>
          <div class="p-6 rounded-3xl flex justify-between items-center text-white bg-brand px-12">
            <div class="container-text w-1/2">
              <div class="flex items-center gap-3"><Video class="w-12 h-12 text-[#D97706]" />
                <h3 class="font-bold font-mont text-white text-3xl">Conteúdo por cliente</h3>
              </div>
              <p class="text-xl mt-3 text-white w-3/4">Associe materiais e procedimentos a empresas específicas para acessar o que importa em cada atendimento.</p>
            </div>
            <div class="container-image w-1/2 flex justify-end">
              <img src="/images/companySection.png" alt="Mockup" class="w-[520px] h-auto rounded-2xl" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits -->
    <section id="beneficios" class="py-16 lg:py-24 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-3xl mx-auto">
        <h2 class="font-mont text-3xl md:text-4xl font-extrabold text-[#0D3D56]">Benefícios que você sente no dia 1</h2>
        <p class="mt-3 text-gray-700">Menos interrupções, mais padrão e conhecimento sempre acessível. Simples e direto.</p>
      </div>
      <div class="mt-10 grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-transform">
          <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-full bg-amber-50 text-amber-600"><Clock class="w-6 h-6" /></div>
            <h3 class="text-xl font-semibold text-[#0D3D56]">Onboarding mais rápido</h3>
          </div>
          <p class="mt-2 text-gray-600">Trilhas claras reduzem a curva de aprendizagem sem depender do sênior.</p>
        </div>
        <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-transform">
          <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-full bg-amber-50 text-amber-600"><Users class="w-6 h-6" /></div>
            <h3 class="text-xl font-semibold text-[#0D3D56]">Execução padronizada</h3>
          </div>
          <p class="mt-2 text-gray-600">Passo a passo por área e cliente garante consistência no dia a dia.</p>
        </div>
        <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-transform">
          <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-full bg-amber-50 text-amber-600"><ShieldCheck class="w-6 h-6" /></div>
            <h3 class="text-xl font-semibold text-[#0D3D56]">Conhecimento preservado</h3>
          </div>
          <p class="mt-2 text-gray-600">O know-how fica documentado e acessível, mesmo com mudanças no time.</p>
        </div>
      </div>
      <div class="mt-8 grid md:grid-cols-2 gap-3">
        <div class="flex items-center gap-3 p-3.5 rounded-lg bg-white border border-gray-200">
          <CheckCircle2 class="w-5 h-5 text-[#D97706]" />
          <span class="text-gray-700">Implantação assistida</span>
        </div>
        <div class="flex items-center gap-3 p-3.5 rounded-lg bg-white border border-gray-200">
          <CheckCircle2 class="w-5 h-5 text-[#D97706]" />
          <span class="text-gray-700">Progresso por etapa</span>
        </div>
        <div class="flex items-center gap-3 p-3.5 rounded-lg bg-white border border-gray-200">
          <CheckCircle2 class="w-5 h-5 text-[#D97706]" />
          <span class="text-gray-700">Acesso por perfil/role</span>
        </div>
        <div class="flex items-center gap-3 p-3.5 rounded-lg bg-white border border-gray-200">
          <CheckCircle2 class="w-5 h-5 text-[#D97706]" />
          <span class="text-gray-700">Uploads seguros em nuvem (S3)</span>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section id="depoimentos" class="py-16 lg:py-24 bg-white border-gray-200">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="font-mont text-3xl md:text-4xl font-extrabold text-center text-[#0D3D56]">O que dizem</h2>
        <div class="mt-12 grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
          <div v-for="(t, idx) in testimonials" :key="idx" class="p-6 rounded-xl border bg-white shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-3">
              <img :src="t.avatar" :alt="`Foto de ${t.author}`" class="h-12 w-12 rounded-full object-cover ring-2 ring-amber-100" />
              <div>
                <div class="text-sm font-semibold text-[#0D3D56]">{{ t.author }}</div>
                <div class="text-xs text-gray-500">{{ t.role }}</div>
              </div>
            </div>
            <blockquote class="mt-4 text-gray-800">{{ t.quote }}</blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Final CTA -->
    <section class="py-16 lg:py-24 text-center mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
      <h2 class="font-mont text-3xl md:text-4xl font-extrabold text-[#0D3D56]">Pronto para otimizar seu escritório?</h2>
      <p class="mt-3 text-gray-700">Agende uma demonstração e veja o Daily Contábil em ação.</p>
      <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="mailto:contato@dailycontabil.com.br" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-md text-white bg-brand-accent hover:brightness-110 transition">Agendar uma Demonstração <ArrowRight class="w-5 h-5" /></a>
        <Link :href="route('login')" class="inline-flex items-center px-7 py-3.5 rounded-md border border-gray-300 bg-white">Acessar o App</Link>
      </div>
    </section>

    <footer class="border-t border-gray-200 py-8 text-center text-sm text-gray-500"> {{ new Date().getFullYear() }} Daily Contábil</footer>
  </div>
</template>

<style scoped>
/* Simple marquee for logos */
.logo-marquee {
  animation: marquee 22s linear infinite;
}
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
</style>

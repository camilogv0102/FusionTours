import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { Search, SlidersHorizontal } from 'lucide-react';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
const Tours = () => {
  const tours = [{
    id: 1,
    name: 'Tiburón Ballena',
    category: 'Agua',
    description: 'Nada junto al pez más grande del océano',
    image: '/images/tours/whale-shark-1.jpg'
  }, {
    id: 2,
    name: 'Cenotes Sagrados',
    category: 'Agua',
    description: 'Sumérgete en los cenotes más hermosos de la Riviera Maya',
    image: '/images/tours/cenote-snorkel.jpg'
  }, {
    id: 3,
    name: 'Holbox Paradise',
    category: 'Aventura',
    description: 'Descubre la isla más hermosa del Caribe mexicano',
    image: '/images/tours/holbox.jpg'
  }, {
    id: 4,
    name: 'Chichen Itza Premium',
    category: 'Cultura',
    description: 'Descubre una de las maravillas del mundo con guía experto',
    image: 'https://images.unsplash.com/photo-1568402102990-bc541580b59f?w=800&q=80'
  }, {
    id: 5,
    name: 'Xcaret Plus',
    category: 'Aventura',
    description: 'El parque más completo de la Riviera Maya',
    image: '/images/tours/xcaret.jpg'
  }, {
    id: 6,
    name: 'Tiburón Ballena VIP',
    category: 'Agua',
    description: 'Experiencia premium con el gigante del mar',
    image: '/images/tours/whale-shark-2.jpg'
  }];
  return <div className="min-h-screen bg-white">
      <Header />
      
      {/* Hero Section - Full Screen */}
      <section className="relative h-screen overflow-hidden">
        <div className="absolute inset-0 bg-cover bg-center" style={{
        backgroundImage: 'url(/images/hero-tours.jpg)'
      }}>
          <div className="absolute inset-0 bg-black/40" />
        </div>
        <div className="relative z-10 h-full max-w-7xl mx-auto px-20 max-md:px-10 max-sm:px-5">
          {/* Title Bottom Left */}
          <div className="absolute bottom-8 left-20 max-md:left-10 max-sm:left-5">
            <h1 className="text-[120px] leading-none font-bold text-white max-md:text-8xl max-sm:text-6xl">
              TOURS
            </h1>
          </div>
          
          {/* Description Bottom Right */}
          <div className="absolute bottom-8 right-20 max-w-xs text-right max-md:right-10 max-sm:right-5 max-sm:max-w-[200px]">
            <p className="text-base text-white leading-relaxed max-sm:text-sm">
              Desde ruinas mayas hasta islas paradisíacas, elige la próxima aventura con Fusion Tours Riviera Maya
            </p>
          </div>
        </div>
      </section>

      {/* Search and Filter Bar */}
      <section className="max-w-7xl mx-auto px-20 py-12 relative z-20 max-md:px-10 max-sm:px-5">
        <div className="flex items-center gap-4 max-md:flex-col">
          <div className="flex-1 flex items-center gap-3 bg-[#E1EAF1] border-2 border-[#0070C0] rounded-full px-6 py-4 max-md:w-full">
            <Search className="text-[#0070C0]" size={20} />
            <input type="text" placeholder="BUSCA TU PRÓXIMA EXPERIENCIA" className="flex-1 outline-none text-sm font-medium text-[#0070C0] placeholder:text-[#0070C0]/60 bg-transparent" />
          </div>
          <button className="flex items-center gap-2 bg-[#E1EAF1] border-2 border-[#0070C0] text-[#0070C0] px-6 py-4 rounded-full font-medium text-sm uppercase hover:bg-[#0070C0] hover:text-white transition-colors max-md:w-full max-md:justify-center">
            <SlidersHorizontal size={20} />
            FILTROS
          </button>
        </div>
      </section>

      {/* Divider Line */}
      <div className="max-w-7xl mx-auto px-20 max-md:px-10 max-sm:px-5">
        <div className="h-[1px] bg-[#0070C0]" />
      </div>

      {/* Tours Section */}
      <section className="max-w-7xl mx-auto px-20 py-20 max-md:px-10 max-sm:px-5">
        {/* Title */}
        <h2 className="text-4xl font-bold text-black mb-12 max-md:text-3xl">
          LOS MÁS BUSCADOS
        </h2>

        {/* First Row - 3 Tours */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
          {tours.slice(0, 3).map((tour, index) => {
            const TourCard = (
              <div className="group relative h-[450px] rounded-2xl overflow-hidden cursor-pointer hover:scale-[1.02] transition-transform duration-300">
                {/* Background Image */}
                <div className="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-500" style={{
                  backgroundImage: `url(${tour.image})`
                }} />
                
                {/* Gradient Overlay */}
                <div className="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/80" />
                
                {/* Category Badge */}
                <div className="absolute top-6 left-6 z-10">
                  <span className="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-bold text-[#0070C0] uppercase">
                    {tour.category}
                  </span>
                </div>
                
                {/* Content */}
                <div className="absolute bottom-0 left-0 right-0 p-6 z-10">
                  <h3 className="text-3xl font-bold text-white mb-2">{tour.name}</h3>
                  <p className="text-white/90 text-sm">
                    {tour.description}
                  </p>
                </div>
              </div>
            );

            // Make first and second tours clickable
            if (index === 0 || index === 1) {
              return (
                <Link key={tour.id} to={`/product/${tour.id}`}>
                  {TourCard}
                </Link>
              );
            }

            return <div key={tour.id}>{TourCard}</div>;
          })}
        </div>

        {/* Divider Line */}
        <div className="h-[1px] bg-[#0070C0] mb-12" />

        {/* Remaining Tours - 3 per row */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
          {tours.slice(3).map(tour => <div key={tour.id} className="group relative h-[450px] rounded-2xl overflow-hidden cursor-pointer hover:scale-[1.02] transition-transform duration-300">
              {/* Background Image */}
              <div className="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-500" style={{
            backgroundImage: `url(${tour.image})`
          }} />
              
              {/* Gradient Overlay */}
              <div className="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/80" />
              
              {/* Category Badge */}
              <div className="absolute top-6 left-6 z-10">
                <span className="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-bold text-[#0070C0] uppercase">
                  {tour.category}
                </span>
              </div>
              
              {/* Content */}
              <div className="absolute bottom-0 left-0 right-0 p-6 z-10">
                <h3 className="text-3xl font-bold text-white mb-2">{tour.name}</h3>
                <p className="text-white/90 text-sm">
                  {tour.description}
                </p>
              </div>
            </div>)}
        </div>

        {/* Ver Más Button */}
        <div className="flex justify-center">
          <button className="border-2 border-black px-12 py-3 rounded-full font-bold text-sm uppercase transition-colors text-zinc-500 bg-neutral-50">
            VER MÁS
          </button>
        </div>
      </section>

      <Footer />
    </div>;
};
export default Tours;

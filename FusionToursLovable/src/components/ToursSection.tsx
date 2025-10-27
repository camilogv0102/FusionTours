import React, { useState, useRef, useEffect } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

const ToursSection = () => {
  const [currentTour, setCurrentTour] = useState(0);
  const [currentVisibleIndex, setCurrentVisibleIndex] = useState(0);
  const scrollRef = useRef<HTMLDivElement>(null);

  const tours = [
    { 
      id: 1, 
      category: "MAR CARIBE", 
      name: "Tiburón Ballena", 
      description: "Nada con el pez más grande del mundo en aguas cristalinas del Caribe Mexicano.",
      image: "/images/tours/whale-shark-1.jpg"
    },
    { 
      id: 2, 
      category: "TIERRA", 
      name: "Xcaret Plus", 
      description: "Parque eco-arqueológico con más de 50 atracciones naturales y culturales.",
      image: "/images/tours/xcaret.jpg"
    },
    { 
      id: 3, 
      category: "AGUA", 
      name: "Cenotes Sagrados", 
      description: "Explora los cenotes más hermosos y místicos de la Riviera Maya.",
      image: "/images/tours/cenote-snorkel.jpg"
    },
    { 
      id: 4, 
      category: "AVENTURA", 
      name: "Holbox", 
      description: "Descubre la isla paradisíaca del Caribe Mexicano con playas vírgenes.",
      image: "/images/tours/holbox.jpg"
    },
    { 
      id: 5, 
      category: "MAR CARIBE", 
      name: "Whale Shark Adventure", 
      description: "Experiencia única nadando con tiburones ballena en su hábitat natural.",
      image: "/images/tours/whale-shark-2.jpg"
    }
  ];

  // Crear array infinito duplicando tours 3 veces
  const infiniteTours = [...tours, ...tours, ...tours];

  const nextTour = () => {
    if (scrollRef.current) {
      const cardWidth = 320 + 24; // width + gap
      scrollRef.current.scrollBy({
        left: cardWidth,
        behavior: 'smooth'
      });
    }
  };

  const prevTour = () => {
    if (scrollRef.current) {
      const cardWidth = 320 + 24; // width + gap
      scrollRef.current.scrollBy({
        left: -cardWidth,
        behavior: 'smooth'
      });
    }
  };

  // Efecto para loop infinito
  useEffect(() => {
    const container = scrollRef.current;
    if (!container) return;

      const handleScroll = () => {
        const { scrollLeft, scrollWidth, clientWidth } = container;
        const singleSetWidth = scrollWidth / 3;
        const cardWidth = 320 + 24; // width + gap

        // Calcular índice visible actual
        const relativeScroll = scrollLeft % singleSetWidth;
        const visibleIndex = Math.round(relativeScroll / cardWidth) % tours.length;
        setCurrentVisibleIndex(visibleIndex);

        // Si llegamos al final del segundo set, resetear al inicio del segundo set
        if (scrollLeft >= singleSetWidth * 2 - cardWidth) {
          container.scrollLeft = singleSetWidth;
        }
        // Si vamos hacia atrás del inicio del segundo set, resetear al final del segundo set
        if (scrollLeft <= singleSetWidth - cardWidth) {
          container.scrollLeft = singleSetWidth * 2 - cardWidth * 2;
        }
      };

    container.addEventListener('scroll', handleScroll, { passive: true });
    
    // Iniciar al principio del segundo set (tarjeta completa visible)
    setTimeout(() => {
      if (container) {
        const singleSetWidth = container.scrollWidth / 3;
        container.scrollLeft = singleSetWidth;
      }
    }, 100);

    return () => container.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <section className="box-border flex w-full flex-col gap-12 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:gap-8">
      <div className="box-border flex justify-between items-start gap-8 m-0 p-0 max-md:flex-col max-md:gap-5">
        <h2 className="box-border text-black text-[32px] font-bold leading-tight m-0 p-0 max-md:text-[28px] max-sm:text-[24px]">
          TOURS EN<br />QUINTANA ROO
        </h2>
        <p className="box-border flex-1 text-[#6F6F6F] text-sm font-normal leading-relaxed m-0 p-0 max-w-[600px] max-md:text-sm">
          ¡Prepárate para descubrir lo mejor del Caribe Mexicano con Fusion Tours Riviera Maya! Te ofrecemos tours y actividades únicas que combinan aventura, cultura y relajación, pensadas para que disfrutes cada segundo de tus vacaciones.
        </p>
      </div>

      <div className="box-border relative w-full m-0 p-0">
        {/* Left arrow - Desktop only */}
        <button 
          onClick={prevTour}
          className="hidden md:flex absolute left-5 top-1/2 -translate-y-1/2 z-20 
            w-[30px] h-[30px] rounded-full bg-white/70 hover:bg-white/90 
            items-center justify-center transition-all hover:scale-110"
          aria-label="Previous tour"
        >
          <ChevronLeft size={18} className="text-gray-800" />
        </button>

        {/* Tour cards container - infinite scroll */}
        <div 
          ref={scrollRef}
          className="box-border flex gap-6 overflow-x-auto m-0 p-0 pb-4 scroll-smooth w-full max-sm:gap-4 scrollbar-hide snap-x snap-mandatory md:snap-none"
        >
          {infiniteTours.map((tour, index) => (
            <div 
              key={`${tour.id}-${index}`}
              className="group relative flex-shrink-0 w-[320px] h-[450px] snap-center
                max-lg:w-[280px] max-lg:h-[400px]
                max-md:w-[260px] max-md:h-[370px]
                max-sm:w-[85vw] max-sm:h-[340px]
                rounded-[20px] overflow-hidden cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl
                bg-white"
              onClick={() => setCurrentTour(index)}
            >
              {/* Imagen - se reduce al 50% en hover (desktop) y en mobile siempre mostrar como hover */}
              <div className="absolute inset-0 transition-all duration-500 
                md:group-hover:top-0 md:group-hover:left-4 md:group-hover:right-4 md:group-hover:bottom-auto md:group-hover:h-[50%] 
                md:group-hover:rounded-[16px] md:group-hover:shadow-lg
                max-md:top-0 max-md:left-4 max-md:right-4 max-md:h-[50%] max-md:rounded-[16px] max-md:shadow-lg
                overflow-hidden">
                <img 
                  src={tour.image} 
                  alt={tour.name}
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent 
                  transition-opacity duration-500 md:group-hover:opacity-0 max-md:opacity-0" />
              </div>
              
              {/* Contenido - pegado al borde inferior en desktop normal, siempre abajo en mobile */}
              <div className="absolute bottom-0 left-0 right-0 px-5 pb-5
                transition-all duration-500
                md:group-hover:top-[52%] md:group-hover:bottom-auto md:group-hover:px-5 md:group-hover:pt-4 md:group-hover:pb-5
                max-md:top-[52%] max-md:px-5 max-md:pt-4">
                
                {/* Título - siempre visible */}
                <h3 className="text-white md:text-white md:group-hover:text-black max-md:text-black text-xl font-bold mb-2 transition-colors duration-300 max-sm:text-lg">
                  {tour.name}
                </h3>
                
                {/* Descripción - mismo texto siempre */}
                <p className="text-white/95 md:text-white/95 md:group-hover:text-gray-700 max-md:text-gray-700 text-sm leading-relaxed transition-colors duration-300 max-sm:text-xs line-clamp-2">
                  {tour.description}
                </p>
                
                {/* Botón - visible en hover desktop y siempre en mobile */}
                <button className="opacity-0 md:opacity-0 md:group-hover:opacity-100 max-md:opacity-100 transition-all duration-300 mt-3
                  flex items-center gap-2 bg-[#0070C0] px-5 py-2.5 rounded-full hover:bg-[#005a9f]">
                  <span className="text-white text-sm font-bold uppercase">VER TOUR</span>
                  <svg width="12" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.5 3.18201C0.223858 3.18201 2.41411e-08 3.40586 0 3.68201C-2.41411e-08 3.95815 0.223858 4.18201 0.5 4.18201L0.5 3.68201L0.5 3.18201ZM15.8536 4.03556C16.0488 3.8403 16.0488 3.52372 15.8536 3.32845L12.6716 0.146474C12.4763 -0.0487882 12.1597 -0.0487882 11.9645 0.146474C11.7692 0.341736 11.7692 0.658319 11.9645 0.853581L14.7929 3.68201L11.9645 6.51043C11.7692 6.7057 11.7692 7.02228 11.9645 7.21754C12.1597 7.4128 12.4763 7.4128 12.6716 7.21754L15.8536 4.03556ZM0.5 3.68201L0.5 4.18201L15.5 4.18201L15.5 3.68201L15.5 3.18201L0.5 3.18201L0.5 3.68201Z" fill="white"/>
                  </svg>
                </button>
              </div>
            </div>
          ))}
        </div>

        {/* Right arrow - Desktop only */}
        <button 
          onClick={nextTour}
          className="hidden md:flex absolute right-5 top-1/2 -translate-y-1/2 z-20 
            w-[30px] h-[30px] rounded-full bg-white/70 hover:bg-white/90 
            items-center justify-center transition-all hover:scale-110"
          aria-label="Next tour"
        >
          <ChevronRight size={18} className="text-gray-800" />
        </button>
      </div>

      {/* Indicadores de paginación - Mobile only */}
      <div className="flex md:hidden justify-center gap-2 mt-4">
        {tours.map((_, index) => (
          <div
            key={index}
            className={`w-2 h-2 rounded-full transition-all ${
              index === currentVisibleIndex 
                ? 'bg-[#FF8B4C] w-6' 
                : 'bg-gray-300'
            }`}
            aria-label={`Tour ${index + 1}`}
          />
        ))}
      </div>
    </section>
  );
};

export default ToursSection;

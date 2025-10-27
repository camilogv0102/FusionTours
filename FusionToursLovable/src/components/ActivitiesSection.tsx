import React, { useState } from 'react';

interface Activity {
  id: number;
  category: string;
  name: string;
  description: string;
  image: string;
}

interface ActivityCardProps {
  activity: Activity;
  isCenter: boolean;
}

const ActivityCard: React.FC<ActivityCardProps> = ({ activity, isCenter }) => {
  return (
    <div 
      className={`relative overflow-hidden rounded-[20px] shadow-2xl transition-all duration-600
        ${isCenter 
          ? 'w-[420px] h-[480px] max-lg:w-[380px] max-lg:h-[440px] max-md:w-[340px] max-md:h-[400px] max-sm:w-[300px] max-sm:h-[360px]' 
          : 'w-[300px] h-[360px] max-lg:w-[260px] max-lg:h-[320px] max-md:w-[220px] max-md:h-[280px]'
        }`}
    >
      {/* Imagen de fondo */}
      <img 
        src={activity.image}
        alt={activity.name}
        className="absolute inset-0 w-full h-full object-cover"
      />
      
      {/* Overlay gradiente */}
      <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />
      
      {/* Contenido de texto - ESQUINA INFERIOR IZQUIERDA */}
      <div className="absolute bottom-0 left-0 right-0 px-6 pb-6 max-sm:px-4 max-sm:pb-4">
        {/* Categoría como texto simple - SOLO en tarjeta central */}
        {isCenter && (
          <p className="text-white/70 text-xs font-bold uppercase tracking-wider mb-1 max-sm:text-[10px]">
            {activity.category}
          </p>
        )}
        
        <h3 className={`text-white font-bold mb-2
          ${isCenter ? 'text-2xl max-lg:text-xl max-sm:text-lg' : 'text-lg max-lg:text-base max-sm:text-sm'}`}>
          {activity.name}
        </h3>
        
        {/* Descripción - SOLO visible en tarjeta central */}
        {isCenter && (
          <p className="text-white/90 text-sm leading-relaxed max-lg:text-xs line-clamp-3">
            {activity.description}
          </p>
        )}
      </div>
    </div>
  );
};

const ActivitiesSection = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isTransitioning, setIsTransitioning] = useState(false);

  const activities: Activity[] = [
    { 
      id: 1,
      category: "ACUÁTICA", 
      name: "Snorkel en Cenote", 
      description: "Explora las aguas cristalinas de los cenotes sagrados mayas.",
      image: "/images/tours/cenote-snorkel.jpg"
    },
    { 
      id: 2,
      category: "TIERRA", 
      name: "Xcaret", 
      description: "Parque eco-arqueológico con espectáculos y naturaleza.",
      image: "/images/tours/xcaret.jpg"
    },
    { 
      id: 3,
      category: "AVENTURA", 
      name: "Holbox", 
      description: "Descubre la isla paradisíaca del Caribe Mexicano.",
      image: "/images/tours/holbox.jpg"
    },
    { 
      id: 4,
      category: "MAR CARIBE", 
      name: "Tiburón Ballena", 
      description: "Nada con el pez más grande del mundo en su hábitat natural.",
      image: "/images/tours/whale-shark-1.jpg"
    },
    { 
      id: 5,
      category: "MAR CARIBE", 
      name: "Whale Shark", 
      description: "Experiencia única nadando con tiburones ballena.",
      image: "/images/tours/whale-shark-2.jpg"
    }
  ];

  const nextSlide = () => {
    if (isTransitioning) return;
    setIsTransitioning(true);
    setCurrentIndex((prev) => (prev + 1) % activities.length);
      setTimeout(() => setIsTransitioning(false), 400);
  };

  const prevSlide = () => {
    if (isTransitioning) return;
    setIsTransitioning(true);
    setCurrentIndex((prev) => (prev - 1 + activities.length) % activities.length);
    setTimeout(() => setIsTransitioning(false), 400);
  };

  // Calcular índices para las 5 tarjetas visibles
  const getPrevPrevIndex = () => (currentIndex - 2 + activities.length) % activities.length;
  const getPrevIndex = () => (currentIndex - 1 + activities.length) % activities.length;
  const getNextIndex = () => (currentIndex + 1) % activities.length;
  const getNextNextIndex = () => (currentIndex + 2) % activities.length;

  return (
    <section id="actividades" className="box-border relative flex w-full flex-col items-center gap-12 bg-[#FFFDE5] m-0 px-20 py-16 max-md:gap-8 max-md:px-10 max-md:py-12 max-sm:px-5 max-sm:py-8 z-0">
      
      {/* Header section */}
      <div className="box-border flex flex-col justify-center items-center gap-6 m-0 p-0 max-w-[800px]">
        <div className="box-border flex justify-center items-center gap-2.5 bg-[#FF8B4C] m-0 px-3 py-1 rounded-full">
          <span className="box-border text-white text-center text-xs font-bold uppercase m-0 p-0">
            CANCUN
          </span>
        </div>
        
        <h2 className="box-border text-[#0070C0] text-center text-[28px] font-bold leading-tight m-0 p-0 max-md:text-[24px] max-sm:text-[20px]">
          ACTIVIDADES IMPERDIBLES EN EL CARIBE MEXICANO
        </h2>
        
        <p className="box-border text-[#6F6F6F] text-center text-sm font-normal leading-relaxed m-0 p-0 max-sm:text-sm">
          Disfruta de experiencias inolvidables en lugares como Xcaret, Xel-Há, Xplor. Sumérgete en ríos subterráneos, deslízate por tirolesas sobre la selva, nada entre peces tropicales o vive espectáculos que te dejarán sin aliento.
        </p>
      </div>

      {/* Nuevo carrusel con 5 tarjetas visibles */}
      <div className="box-border w-full relative m-0 p-0 flex items-center justify-center">
        
        {/* Contenedor de las 5 tarjetas */}
        <div className="relative w-full max-w-[1200px] h-[540px] flex items-center justify-center 
          max-md:max-w-[900px] max-md:h-[480px] max-sm:h-[400px]">
          
          {/* Tarjeta EXTREMO IZQUIERDO (más pequeña, más atrás) */}
          <div 
            className={`absolute left-[50px] top-1/2 -translate-y-1/2 z-0 transition-all duration-400 ease-out
              ${isTransitioning ? 'scale-75' : 'scale-75'}
              max-lg:left-[30px] max-lg:scale-[0.65]
              max-md:hidden`}
            style={{ filter: 'brightness(0.6)' }}
          >
            <ActivityCard activity={activities[getPrevPrevIndex()]} isCenter={false} />
          </div>

          {/* Tarjeta IZQUIERDA (mediana, semi-atrás) */}
          <div 
            className={`absolute left-[140px] top-1/2 -translate-y-1/2 z-10 transition-all duration-400 ease-out
              ${isTransitioning ? 'scale-80' : 'scale-85'}
              max-lg:left-[100px] max-lg:scale-[0.75]
              max-md:left-[50px] max-md:scale-[0.7]
              max-sm:hidden`}
            style={{ filter: 'brightness(0.75)' }}
          >
            <ActivityCard activity={activities[getPrevIndex()]} isCenter={false} />
          </div>

          {/* Tarjeta CENTRAL (grande, al frente) */}
          <div 
            className={`absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-30 transition-all duration-400 ease-out
              ${isTransitioning ? 'scale-110 rotate-2' : 'scale-100 rotate-0'}`}
          >
            <ActivityCard activity={activities[currentIndex]} isCenter={true} />
          </div>

          {/* Tarjeta DERECHA (mediana, semi-atrás) */}
          <div 
            className={`absolute right-[140px] top-1/2 -translate-y-1/2 z-10 transition-all duration-400 ease-out
              ${isTransitioning ? 'scale-80' : 'scale-85'}
              max-lg:right-[100px] max-lg:scale-[0.75]
              max-md:right-[50px] max-md:scale-[0.7]
              max-sm:hidden`}
            style={{ filter: 'brightness(0.75)' }}
          >
            <ActivityCard activity={activities[getNextIndex()]} isCenter={false} />
          </div>

          {/* Tarjeta EXTREMO DERECHO (más pequeña, más atrás) */}
          <div 
            className={`absolute right-[50px] top-1/2 -translate-y-1/2 z-0 transition-all duration-400 ease-out
              ${isTransitioning ? 'scale-75' : 'scale-75'}
              max-lg:right-[30px] max-lg:scale-[0.65]
              max-md:hidden`}
            style={{ filter: 'brightness(0.6)' }}
          >
            <ActivityCard activity={activities[getNextNextIndex()]} isCenter={false} />
          </div>

          {/* Flecha IZQUIERDA - oculta en mobile */}
          <button 
            onClick={prevSlide}
            disabled={isTransitioning}
            className="hidden md:block absolute left-[-80px] top-1/2 -translate-y-1/2 z-40 
              hover:scale-110 transition-transform disabled:opacity-50 disabled:cursor-not-allowed
              max-lg:left-[-60px] md:max-lg:left-[-50px]"
            aria-label="Previous activity"
          >
            <svg width="49" height="50" viewBox="0 0 49 50" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="49" height="50" rx="24.5" transform="matrix(-1 0 0 1 49 0)" fill="#FF8B4C"/>
              <path d="M32 24C32.5523 24 33 24.4477 33 25C33 25.5523 32.5523 26 32 26L32 25L32 24ZM16.2929 25.7071C15.9024 25.3166 15.9024 24.6834 16.2929 24.2929L22.6569 17.9289C23.0474 17.5384 23.6805 17.5384 24.0711 17.9289C24.4616 18.3195 24.4616 18.9526 24.0711 19.3431L18.4142 25L24.0711 30.6569C24.4616 31.0474 24.4616 31.6805 24.0711 32.0711C23.6805 32.4616 23.0474 32.4616 22.6569 32.0711L16.2929 25.7071ZM32 25L32 26L17 26L17 25L17 24L32 24L32 25Z" fill="white"/>
            </svg>
          </button>

          {/* Flecha DERECHA - oculta en mobile */}
          <button 
            onClick={nextSlide}
            disabled={isTransitioning}
            className="hidden md:block absolute right-[-80px] top-1/2 -translate-y-1/2 z-40 
              hover:scale-110 transition-transform disabled:opacity-50 disabled:cursor-not-allowed
              max-lg:right-[-60px] md:max-lg:right-[-50px]"
            aria-label="Next activity"
          >
            <svg width="49" height="50" viewBox="0 0 49 50" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="49" height="50" rx="24.5" fill="#FF8B4C"/>
              <path d="M17 24C16.4477 24 16 24.4477 16 25C16 25.5523 16.4477 26 17 26L17 25L17 24ZM32.7071 25.7071C33.0976 25.3166 33.0976 24.6834 32.7071 24.2929L26.3431 17.9289C25.9526 17.5384 25.3195 17.5384 24.9289 17.9289C24.5384 18.3195 24.5384 18.9526 24.9289 19.3431L30.5858 25L24.9289 30.6569C24.5384 31.0474 24.5384 31.6805 24.9289 32.0711C25.3195 32.4616 25.9526 32.4616 26.3431 32.0711L32.7071 25.7071ZM17 25L17 26L32 26L32 25L32 24L17 24L17 25Z" fill="white"/>
            </svg>
          </button>

        </div>
      </div>

      {/* Indicadores de paginación - Mobile only */}
      <div className="flex md:hidden justify-center gap-2 mt-6">
        {activities.map((_, index) => (
          <button
            key={index}
            onClick={() => {
              if (!isTransitioning) {
                setIsTransitioning(true);
                setCurrentIndex(index);
                setTimeout(() => setIsTransitioning(false), 400);
              }
            }}
            className={`w-2 h-2 rounded-full transition-all ${
              index === currentIndex 
                ? 'bg-[#FF8B4C] w-6' 
                : 'bg-gray-300'
            }`}
            aria-label={`Ir a actividad ${index + 1}`}
          />
        ))}
      </div>
    </section>
  );
};

export default ActivitiesSection;

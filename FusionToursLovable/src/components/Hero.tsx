import React from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import Header from './Header';

const Hero = () => {
  const navigate = useNavigate();
  const location = useLocation();

  const handleActivitiesClick = () => {
    if (location.pathname !== '/') {
      navigate('/');
      setTimeout(() => {
        document.getElementById('actividades')?.scrollIntoView({ behavior: 'smooth' });
      }, 100);
    } else {
      document.getElementById('actividades')?.scrollIntoView({ behavior: 'smooth' });
    }
  };

  return <section className="box-border relative w-full h-[700px] overflow-hidden m-0 p-0 flex flex-col justify-end max-md:h-[600px] max-sm:h-[500px]">
      <img src="https://api.builder.io/api/v1/image/assets/TEMP/c2617f60c3d2434f45a8cfeabbb96b65faa762e9?width=3024" alt="Beautiful tropical beach with palm trees and ocean" className="box-border w-full h-full object-cover absolute m-0 p-0 left-0 top-0 z-0" />
      
      <div className="absolute top-0 left-0 right-0 z-10">
        <Header />
      </div>
      
      {/* Bottom content container */}
      <div className="box-border relative z-10 flex flex-col gap-6 m-0 px-20 pb-8 max-md:px-10 max-md:pb-6 max-sm:px-5 max-sm:pb-4 max-sm:gap-4">
        {/* Desktop layout */}
        <div className="max-sm:hidden">
          <div className="box-border flex flex-row justify-between items-center gap-8 m-0 p-0 max-md:flex-col max-md:gap-4 max-md:items-start">
            <p className="box-border text-white text-base font-normal leading-relaxed m-0 p-0">
              Vive la experiencia del Caribe Mexicano
            </p>
            <div className="box-border flex items-center gap-3 m-0 p-0">
              <Link to="/tours">
                <button className="box-border flex justify-center items-center gap-3 m-0 px-6 py-2.5 rounded-full bg-[#FF8B4C] hover:bg-[#ff7a3a] transition-colors">
                  <span className="box-border text-white text-center text-sm font-bold uppercase m-0 p-0">Tours</span>
                </button>
              </Link>
              <button onClick={handleActivitiesClick} className="box-border flex justify-center items-center gap-3 m-0 px-6 py-2.5 rounded-full bg-[#0070C0] hover:bg-[#005a9f] transition-colors">
                <span className="box-border text-white text-center text-sm font-bold uppercase m-0 p-0">Actividades</span>
                <span className="box-border text-white text-lg m-0 p-0">→</span>
              </button>
            </div>
          </div>
        </div>

        {/* Mobile layout */}
        <div className="sm:hidden flex flex-col gap-3">
          {/* Slogan arriba */}
          <p className="box-border text-white text-sm font-normal leading-relaxed m-0 p-0 text-center">
            Vive la experiencia del Caribe Mexicano
          </p>
          
          {/* Botones lado a lado */}
          <div className="box-border flex items-center gap-2 m-0 p-0 justify-center">
            <Link to="/tours" className="flex-1 max-w-[160px]">
              <button className="box-border w-full flex justify-center items-center gap-2 m-0 px-4 py-2.5 rounded-full bg-[#FF8B4C] hover:bg-[#ff7a3a] transition-colors">
                <span className="box-border text-white text-center text-xs font-bold uppercase m-0 p-0">Tours</span>
              </button>
            </Link>
            <button onClick={handleActivitiesClick} className="flex-1 max-w-[160px] box-border flex justify-center items-center gap-2 m-0 px-4 py-2.5 rounded-full bg-[#0070C0] hover:bg-[#005a9f] transition-colors">
              <span className="box-border text-white text-center text-xs font-bold uppercase m-0 p-0">Actividades</span>
              <span className="box-border text-white text-sm m-0 p-0">→</span>
            </button>
          </div>
        </div>
        
        {/* Title - bottom */}
        <h1 className="box-border text-white font-black leading-[0.9] tracking-tighter uppercase m-0 p-0 text-center text-5xl sm:text-7xl md:text-8xl lg:text-9xl max-sm:text-4xl max-sm:leading-[1]">
          FUSION TOURS
        </h1>
      </div>
    </section>;
};
export default Hero;
import React from 'react';
import Header from '@/components/Header';
import Footer from '@/components/Footer';

const About = () => {
  return (
    <div className="min-h-screen bg-white">
      <Header />
      
      <main>
        {/* Hero Section with Title and Description */}
        <section className="pt-[141px] max-md:pt-[115px] max-sm:pt-[105px]">
          <div className="max-w-[1400px] mx-auto px-20 max-md:px-10 max-sm:px-5 py-16 max-md:py-12 max-sm:py-8">
            {/* Main Title */}
            <h1 className="text-[#2D2D2D] text-[42px] md:text-[52px] lg:text-[56px] font-bold leading-tight tracking-tight mb-6">
              CONOCE A FUSION TOURS
            </h1>
            
            {/* Decorative Line */}
            <div className="w-full h-[2px] bg-[#0070C0] mb-8" />
            
            {/* Two Column Text */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-md:gap-6">
              <p className="text-[#2D2D2D] text-base md:text-lg leading-relaxed font-normal">
                Nuestro compromiso es ofrecer recorridos auténticos, seguros y llenos de emoción, diseñados para que cada visitante descubra la esencia de la Riviera Maya de una forma diferente.
              </p>
              <p className="text-[#2D2D2D] text-base md:text-lg leading-relaxed font-normal">
                En Fusion Tours Riviera Maya somos una agencia dedicada a crear experiencias únicas que fusionan la aventura, la cultura y la belleza natural del Caribe Mexicano.
              </p>
            </div>
          </div>
        </section>

        {/* Main Image Section */}
        <section className="max-w-[1400px] mx-auto px-20 max-md:px-10 max-sm:px-5 pb-16 max-md:pb-12 max-sm:pb-8">
          <div className="w-full h-[350px] md:h-[450px] lg:h-[500px] rounded-3xl overflow-hidden shadow-lg">
            <img 
              src="/images/about-hero.jpg"
              alt="Vista panorámica de Cancún y la Riviera Maya al atardecer - Fusion Tours"
              className="w-full h-full object-cover object-center"
            />
          </div>
        </section>

        {/* Additional Content Section with Light Blue Background */}
        <section className="bg-[#F0F8FF] py-16 max-md:py-12 max-sm:py-8">
          <div className="max-w-[1400px] mx-auto px-20 max-md:px-10 max-sm:px-5">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-12 max-md:gap-8 max-sm:gap-6">
              {/* Left Column - Italic Text */}
              <div>
                <p className="text-[#2D2D2D] text-base md:text-lg leading-relaxed italic font-normal">
                  Más que una agencia, somos tus aliados de viaje. Queremos que vivas la Riviera Maya desde el corazón, conectando con su naturaleza, su historia y su gente.
                </p>
              </div>
              
              {/* Right Column - Regular Text */}
              <div>
                <p className="text-[#2D2D2D] text-base md:text-lg leading-relaxed font-normal">
                  Con años de experiencia en el sector turístico, nuestro equipo combina profesionalismo y pasión por el servicio. Colaboramos con guías certificados y proveedores locales para garantizar calidad, confianza y sostenibilidad en cada tour. Desde cenotes y zonas arqueológicas, hasta excursiones marítimas y parques, en Fusion Tours creemos que cada viaje debe convertirse en un recuerdo inolvidable.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Final Call to Action */}
        <section className="py-20 max-md:py-16 max-sm:py-12">
          <div className="max-w-[1400px] mx-auto px-20 max-md:px-10 max-sm:px-5">
            <h2 className="text-[#2D2D2D] text-[36px] md:text-[48px] lg:text-[56px] font-bold text-center leading-tight">
              Vive, explora y siente la verdadera fusión del paraíso.
            </h2>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default About;

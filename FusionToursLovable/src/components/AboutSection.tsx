import React from 'react';

const AboutSection = () => {
  const stats = [
    { number: "+20", description: "AÑOS DE EXPERIENCIA EN MERCADO" },
    { number: "+120", description: "CLIENTES SATISFECHOS EN 5 AÑOS" },
    { number: "+40", description: "TOURS Y ACTIVIDADES VERIFICADAS" }
  ];

  return (
    <section className="box-border flex w-full flex-col items-start gap-10 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:gap-8">
      <div className="box-border flex justify-between items-start gap-8 w-full m-0 p-0 max-md:flex-col max-md:gap-5">
        <h2 className="box-border text-black text-[32px] font-bold leading-tight m-0 p-0 max-md:text-[28px] max-sm:text-[24px]">
          CONOCE SOBRE<br />FUSION TOURS
        </h2>
        <p className="box-border flex-1 text-[#6F6F6F] text-sm font-normal leading-relaxed m-0 p-0 max-w-[600px]">
          Fusion Tours Riviera Maya nació con una misión clara: ofrecer experiencias auténticas, seguras y llenas de emoción. Tu satisfacción es nuestra mayor recompensa.
        </p>
      </div>

      <div className="box-border h-[400px] w-full relative m-0 p-0 max-md:h-[300px]">
        <div 
          className="box-border w-full h-full bg-cover bg-center m-0 p-0 rounded-lg"
          style={{
            backgroundImage: "url('https://api.builder.io/api/v1/image/assets/TEMP/745b74eac0718e6fe5533f67aae81a3c03297a50?width=2866')"
          }}
        />
        <button className="box-border inline-flex justify-center items-center gap-3 absolute bg-[#0070C0] m-0 px-6 py-2.5 rounded-full right-6 bottom-6 hover:bg-[#005a9f] transition-colors">
          <span className="box-border text-white text-center text-sm font-bold uppercase m-0 p-0">
            CONOCENOS
          </span>
        </button>
      </div>

      <div className="box-border flex justify-around items-start w-full m-0 p-0 max-md:flex-col max-md:gap-8">
        {stats.map((stat, index) => (
          <div key={index} className="box-border flex items-end gap-4 m-0 p-0 max-sm:flex-col max-sm:items-start max-sm:gap-2">
            <div className="box-border text-black text-[72px] font-bold leading-none m-0 p-0 max-md:text-[60px] max-sm:text-[48px]">
              {stat.number}
            </div>
            <div className="box-border w-[140px] text-[#686868] text-sm font-bold leading-tight mb-2 m-0 p-0 max-sm:w-full max-sm:text-xs">
              {stat.description}
            </div>
          </div>
        ))}
      </div>
    </section>
  );
};

export default AboutSection;

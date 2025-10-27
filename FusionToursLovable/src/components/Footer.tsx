import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  const [email, setEmail] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Handle newsletter subscription
    console.log('Newsletter subscription:', email);
    setEmail('');
  };

  return (
    <footer className="box-border flex flex-col justify-center items-center gap-[60px] w-full bg-[#E1EAF1] m-0 px-[50px] py-[63px] rounded-[60px_60px_0_0] max-md:gap-10 max-md:px-[30px] max-md:py-10 max-sm:px-5 max-sm:py-[30px]">
      {/* Top section */}
      <div className="box-border flex h-[197px] justify-between items-center w-full m-0 p-0 border-b-[rgba(218,218,218,0.35)] border-b border-solid max-md:flex-col max-md:gap-[30px] max-md:h-auto">
        <div className="box-border flex w-[864.5px] flex-col items-start gap-[31px] m-0 p-0 max-md:w-full">
          <img
            src="https://api.builder.io/api/v1/image/assets/TEMP/40785c6a2c03bd25493e01f0ad9ae96c58090ee6?width=574"
            alt="Fusion Tours Logo"
            className="box-border w-[287px] h-[119px] m-0 p-0"
          />
          
          {/* Social media links */}
          <div className="box-border flex items-center gap-[3.75px] m-0 p-0">
            <a href="#" className="box-border flex w-[37.5px] h-[37.5px] flex-col justify-center items-center gap-[7.5px] m-0 p-0 rounded-[75px] border-[0.75px] border-solid border-[#0070C0] hover:bg-[#0070C0] hover:text-white transition-colors" aria-label="Facebook">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.5 9C16.5 4.86 13.14 1.5 9 1.5C4.86 1.5 1.5 4.86 1.5 9C1.5 12.63 4.08 15.6525 7.5 16.35V11.25H6V9H7.5V7.125C7.5 5.6775 8.6775 4.5 10.125 4.5H12V6.75H10.5C10.0875 6.75 9.75 7.0875 9.75 7.5V9H12V11.25H9.75V16.4625C13.5375 16.0875 16.5 12.8925 16.5 9Z" fill="#0070C0"/>
              </svg>
            </a>
            
            <a href="#" className="box-border flex w-[37.5px] h-[37.5px] flex-col justify-center items-center gap-[7.5px] m-0 p-0 rounded-[75px] border-[0.75px] border-solid border-[#0070C0] hover:bg-[#0070C0] hover:text-white transition-colors" aria-label="Twitter">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.845 4.5C16.2675 4.7625 15.645 4.935 15 5.0175C15.66 4.62 16.17 3.99 16.41 3.2325C15.7875 3.6075 15.0975 3.87 14.37 4.02C13.7775 3.375 12.945 3 12 3C10.2375 3 8.79753 4.44 8.79753 6.2175C8.79753 6.4725 8.82753 6.72 8.88003 6.9525C6.21003 6.8175 3.83253 5.535 2.25003 3.5925C1.97253 4.065 1.81503 4.62 1.81503 5.205C1.81503 6.3225 2.37753 7.3125 3.24753 7.875C2.71503 7.875 2.22003 7.725 1.78503 7.5V7.5225C1.78503 9.0825 2.89503 10.3875 4.36503 10.68C3.89316 10.8097 3.39756 10.8277 2.91753 10.7325C3.12123 11.3719 3.52018 11.9313 4.05829 12.3322C4.5964 12.7331 5.24661 12.9553 5.91753 12.9675C4.78028 13.8679 3.37054 14.3545 1.92003 14.3475C1.66503 14.3475 1.41003 14.3325 1.15503 14.3025C2.58003 15.2175 4.27503 15.75 6.09003 15.75C12 15.75 15.2475 10.845 15.2475 6.5925C15.2475 6.45 15.2475 6.315 15.24 6.1725C15.87 5.7225 16.41 5.1525 16.845 4.5Z" fill="#0070C0"/>
              </svg>
            </a>
            
            <a href="#" className="box-border flex w-[37.5px] h-[37.5px] flex-col justify-center items-center gap-[7.5px] m-0 p-0 rounded-[75px] border-[0.75px] border-solid border-[#0070C0] hover:bg-[#0070C0] hover:text-white transition-colors" aria-label="Instagram">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2.25C12.9946 2.25 13.9484 2.64509 14.6517 3.34835C15.3549 4.05161 15.75 5.00544 15.75 6V12C15.75 12.9946 15.3549 13.9484 14.6517 14.6517C13.9484 15.3549 12.9946 15.75 12 15.75H6C5.00544 15.75 4.05161 15.3549 3.34835 14.6517C2.64509 13.9484 2.25 12.9946 2.25 12V6C2.25 5.00544 2.64509 4.05161 3.34835 3.34835C4.05161 2.64509 5.00544 2.25 6 2.25H12ZM9 6C8.20435 6 7.44129 6.31607 6.87868 6.87868C6.31607 7.44129 6 8.20435 6 9C6 9.79565 6.31607 10.5587 6.87868 11.1213C7.44129 11.6839 8.20435 12 9 12C9.79565 12 10.5587 11.6839 11.1213 11.1213C11.6839 10.5587 12 9.79565 12 9C12 8.20435 11.6839 7.44129 11.1213 6.87868C10.5587 6.31607 9.79565 6 9 6ZM9 7.5C9.39782 7.5 9.77936 7.65804 10.0607 7.93934C10.342 8.22064 10.5 8.60218 10.5 9C10.5 9.39782 10.342 9.77936 10.0607 10.0607C9.77936 10.342 9.39782 10.5 9 10.5C8.60218 10.5 8.22064 10.342 7.93934 10.0607C7.65804 9.77936 7.5 9.39782 7.5 9C7.5 8.60218 7.65804 8.22064 7.93934 7.93934C8.22064 7.65804 8.60218 7.5 9 7.5ZM12.375 4.875C12.1761 4.875 11.9853 4.95402 11.8447 5.09467C11.704 5.23532 11.625 5.42609 11.625 5.625C11.625 5.82391 11.704 6.01468 11.8447 6.15533C11.9853 6.29598 12.1761 6.375 12.375 6.375C12.5739 6.375 12.7647 6.29598 12.9053 6.15533C13.046 6.01468 13.125 5.82391 13.125 5.625C13.125 5.42609 13.046 5.23532 12.9053 5.09467C12.7647 4.95402 12.5739 4.875 12.375 4.875Z" fill="#0070C0"/>
              </svg>
            </a>
          </div>
        </div>
        
        <div className="box-border w-[445px] text-[#0070C0] text-[15px] font-bold leading-[25px] m-0 p-0 max-md:w-full">
          Fusion Tours te conecta con las experiencias más auténticas de México. Descubre aventuras inolvidables, explora la naturaleza y sumérgete en la cultura local con nuestros tours guiados.
        </div>
      </div>

      {/* Middle section */}
      <div className="box-border flex justify-between items-start w-full m-0 pb-[60px] p-0 border-b-[rgba(218,218,218,0.35)] border-b border-solid max-md:flex-col max-md:gap-10">
        <div className="box-border flex items-start gap-[90px] m-0 p-0 max-md:gap-10 max-sm:flex-col max-sm:gap-[30px]">
          <div className="box-border flex flex-col items-start gap-[49px] m-0 p-0">
            <h3 className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">TOURS</h3>
            <nav className="box-border flex flex-col items-start gap-3 m-0 p-0">
              <a href="/tours" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Ver todos los tours</a>
              <Link to="/product/1" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Tiburón Ballena</Link>
              <Link to="/product/2" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Cenotes Sagrados</Link>
            </nav>
          </div>
          
          <div className="box-border flex flex-col items-start gap-[49px] m-0 p-0">
            <h3 className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">ACTIVIDADES</h3>
            <nav className="box-border flex flex-col items-start gap-3 m-0 p-0">
              <a href="/#actividades" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Ver actividades</a>
              <a href="/#actividades" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Snorkel</a>
              <a href="/#actividades" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Buceo</a>
            </nav>
          </div>
          
          <div className="box-border flex flex-col items-start gap-[49px] m-0 p-0">
            <h3 className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">BLOG</h3>
            <nav className="box-border flex flex-col items-start gap-3 m-0 p-0">
              <a href="/blog" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Todos los artículos</a>
              <a href="/blog/mejores-cenotes" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Mejores cenotes</a>
            </nav>
          </div>
          
          <div className="box-border flex flex-col items-start gap-[49px] m-0 p-0">
            <h3 className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">INFORMACIÓN</h3>
            <nav className="box-border flex flex-col items-start gap-3 m-0 p-0">
              <a href="/contacto" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Contáctanos</a>
              <a href="/faq" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Preguntas Frecuentes</a>
              <a href="/about" className="box-border text-[#0070C0] text-xl font-normal leading-8 m-0 p-0 hover:underline">Sobre Nosotros</a>
            </nav>
          </div>
        </div>
        
        <div className="box-border flex w-[484px] flex-col items-start gap-[39px] m-0 p-0 max-md:w-full">
          <div className="box-border flex flex-col items-start gap-5 m-0 p-0">
            <h3 className="box-border w-[464px] text-[#0070C0] text-[26px] font-bold leading-[41px] m-0 p-0 max-md:w-full">
              ¿No sabes por dónde empezar?
            </h3>
            <p className="box-border w-[357px] text-[#0070C0] text-lg font-normal leading-7 m-0 p-0 max-md:w-full">
              Contáctanos por WhatsApp y te ayudaremos a planear tu aventura perfecta
            </p>
          </div>
          
          <div className="box-border flex items-center gap-5 w-full m-0 p-0">
            <a href="https://wa.me/1234567890" className="hover:scale-110 transition-transform" aria-label="Contact us on WhatsApp">
              <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.0021 4.1665C36.5083 4.1665 45.8354 13.4936 45.8354 24.9998C45.8354 36.5061 36.5083 45.8332 25.0021 45.8332C21.3203 45.8395 17.7033 44.8651 14.5229 43.0103L4.17707 45.8332L6.99373 35.4832C5.1374 32.3018 4.16228 28.6832 4.16873 24.9998C4.16873 13.4936 13.4958 4.1665 25.0021 4.1665ZM17.9021 15.2082L17.4854 15.2248C17.216 15.2434 16.9528 15.3142 16.7104 15.4332C16.4845 15.5613 16.2782 15.7213 16.0979 15.9082C15.8479 16.1436 15.7062 16.3478 15.5541 16.5457C14.7836 17.5476 14.3687 18.7776 14.375 20.0415C14.3791 21.0623 14.6458 22.0561 15.0625 22.9853C15.9146 24.8644 17.3166 26.854 19.1666 28.6978C19.6125 29.1415 20.05 29.5873 20.5208 30.0019C22.8196 32.0257 25.5589 33.4852 28.5208 34.2644L29.7042 34.4457C30.0896 34.4665 30.475 34.4373 30.8625 34.4186C31.4691 34.3866 32.0614 34.2223 32.5979 33.9373C32.8705 33.7964 33.1367 33.6435 33.3958 33.479C33.3958 33.479 33.484 33.4193 33.6562 33.2915C33.9375 33.0832 34.1104 32.9353 34.3437 32.6915C34.5187 32.5109 34.6646 32.3012 34.7812 32.0623C34.9437 31.7228 35.1062 31.0748 35.1729 30.5353C35.2229 30.1228 35.2083 29.8978 35.2021 29.7582C35.1937 29.5353 35.0083 29.304 34.8062 29.2061L33.5937 28.6623C33.5937 28.6623 31.7812 27.8728 30.6729 27.3686C30.5569 27.3181 30.4326 27.2891 30.3062 27.2832C30.1637 27.2683 30.0196 27.2842 29.8837 27.3298C29.7478 27.3755 29.6234 27.4498 29.5187 27.5478C29.5083 27.5436 29.3687 27.6623 27.8625 29.4873C27.776 29.6035 27.6569 29.6913 27.5204 29.7395C27.3839 29.7878 27.2361 29.7943 27.0958 29.7582C26.96 29.722 26.8271 29.676 26.6979 29.6207C26.4396 29.5123 26.35 29.4707 26.1729 29.3957C24.9768 28.8746 23.8697 28.1696 22.8916 27.3061C22.6292 27.0769 22.3854 26.8269 22.1354 26.5853C21.3158 25.8003 20.6015 24.9123 20.0104 23.9436L19.8875 23.7457C19.8005 23.6119 19.7292 23.4686 19.675 23.3186C19.5958 23.0123 19.8021 22.7665 19.8021 22.7665C19.8021 22.7665 20.3083 22.2123 20.5437 21.9123C20.7729 21.6207 20.9667 21.3373 21.0917 21.1353C21.3375 20.7394 21.4146 20.3332 21.2854 20.0186C20.7021 18.5936 20.0993 17.1762 19.4771 15.7665C19.3541 15.4873 18.9896 15.2873 18.6583 15.2478C18.5458 15.2339 18.4333 15.2228 18.3208 15.2144C18.0411 15.1984 17.7606 15.2012 17.4812 15.2228L17.9021 15.2082Z" fill="#0070C0"/>
              </svg>
            </a>
          </div>
        </div>
      </div>

      {/* Bottom section */}
      <div className="box-border flex justify-between items-center w-full m-0 p-0 max-sm:flex-col max-sm:gap-5 max-sm:text-center">
        <div className="box-border flex justify-center items-center gap-[30px] m-0 p-0">
          <span className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">MADE WITH LOVE FOR</span>
          <div className="box-border w-[46px] h-px bg-[#0070C0] m-0 p-0" />
          <span className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">SNC DESIGNS</span>
        </div>
        <div className="box-border text-[#0070C0] text-sm font-bold m-0 p-0">
          TODOS LOS DERECHOS RESERVADOS | FUSION TOURS
        </div>
      </div>
    </footer>
  );
};

export default Footer;

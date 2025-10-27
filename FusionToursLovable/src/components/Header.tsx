import React, { useState } from 'react';
import { Menu, X } from 'lucide-react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useLanguage } from '@/contexts/LanguageContext';
import { useCurrency } from '@/contexts/CurrencyContext';

const Header = () => {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [isLangDropdownOpen, setIsLangDropdownOpen] = useState(false);
  const [isCurrencyDropdownOpen, setIsCurrencyDropdownOpen] = useState(false);
  
  const location = useLocation();
  const navigate = useNavigate();
  const { language, setLanguage } = useLanguage();
  const { currency, setCurrency } = useCurrency();
  
  const isToursPage = location.pathname === '/tours';
  const isProductPage = location.pathname.startsWith('/product/');
  
  // White header for product pages, semi-transparent for others
  const headerBg = isProductPage ? 'bg-white' : isToursPage ? 'bg-transparent' : 'bg-[rgba(45,45,45,0.40)]';
  const logoColor = isProductPage ? '#2D2D2D' : '#FFFDE5';
  const textColor = isProductPage ? '#2D2D2D' : '#FFFDE5';
  const iconColor = isProductPage ? '#2D2D2D' : '#FFFDE5';

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

  return (
    <header className={`box-border flex w-[calc(100%_-_40px)] max-w-[1472px] h-[81px] justify-between items-center backdrop-blur-[54.29999923706055px] m-0 px-10 py-[25px] rounded-[10px] left-2/4 max-lg:px-8 max-lg:py-5 max-md:px-6 max-md:py-4 max-md:h-[70px] max-sm:px-4 max-sm:py-3 max-sm:h-[60px] max-sm:w-[calc(100%_-_20px)] fixed -translate-x-2/4 z-50 ${headerBg} top-[30px] max-sm:top-[15px]`}>
      {/* Logo */}
      <Link to="/" className="flex items-center cursor-pointer hover:opacity-80 transition-opacity">
        <svg width="150" height="33" viewBox="0 0 150 33" fill="none" xmlns="http://www.w3.org/2000/svg" className="logo max-md:w-[120px] max-sm:w-[100px] max-sm:h-[20px]">
          <text fill={logoColor} xmlSpace="preserve" style={{whiteSpace: 'pre'}} fontFamily="Neue Montreal" fontSize="43.8721" fontWeight="bold" letterSpacing="0em">
            <tspan x="0" y="32.4391">FS</tspan>
          </text>
        </svg>
      </Link>

      {/* Desktop Navigation */}
      <nav className="box-border flex items-center gap-[30px] m-0 p-0 max-lg:gap-5 max-md:hidden">
        <Link to="/tours" className="box-border flex justify-center items-center m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity">
          <span className="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" style={{ color: textColor }}>
            tours
          </span>
        </Link>
        <button onClick={handleActivitiesClick} className="box-border flex justify-center items-center m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity">
          <span className="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" style={{ color: textColor }}>
            actividades
          </span>
        </button>
        <Link to="/about" className="box-border flex justify-center items-center m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity">
          <span className="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" style={{ color: textColor }}>
            sobre nosotros
          </span>
        </Link>
      </nav>

      {/* Right side navigation */}
      <div className="box-border flex items-center gap-10 m-0 p-0 max-lg:gap-6 max-md:gap-4">
        <div className="box-border flex items-center gap-[30px] m-0 p-0 max-lg:gap-5 max-md:hidden">
          {/* Language Selector */}
          <div className="relative">
            <button
              onClick={() => setIsLangDropdownOpen(!isLangDropdownOpen)}
              className="box-border flex justify-center items-center gap-[15px] m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity"
            >
              <span className="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" style={{ color: textColor }}>
                {language === 'es' ? 'ES' : 'EN'}
              </span>
              <svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.95007 3.94964C3.86649 4.03325 3.76726 4.09956 3.65805 4.14481C3.54884 4.19006 3.43178 4.21335 3.31357 4.21335C3.19535 4.21335 3.0783 4.19006 2.96909 4.14481C2.85987 4.09956 2.76064 4.03325 2.67707 3.94964L0.277068 1.54964C0.190585 1.46672 0.121529 1.36737 0.0739491 1.25741C0.0263693 1.14745 0.00122424 1.0291 -1.22098e-05 0.90929C-0.00124866 0.789484 0.0214488 0.670637 0.0667488 0.559719C0.112049 0.4488 0.179041 0.348043 0.263794 0.263356C0.348548 0.178669 0.449357 0.111757 0.56031 0.0665441C0.671264 0.0213309 0.790129 -0.00127264 0.909934 5.77203e-05C1.02974 0.00138809 1.14807 0.0266266 1.25799 0.0742927C1.36792 0.121959 1.46722 0.191092 1.55007 0.277641L3.31407 2.04064L5.07707 0.27764C5.15992 0.191092 5.25922 0.121958 5.36914 0.0742923C5.47906 0.0266262 5.5974 0.00138769 5.7172 5.73e-05C5.83701 -0.00127309 5.95587 0.0213304 6.06683 0.0665436C6.17778 0.111757 6.27859 0.178669 6.36334 0.263355C6.4481 0.348042 6.51509 0.4488 6.56039 0.559718C6.60569 0.670636 6.62839 0.789483 6.62715 0.909289C6.62591 1.0291 6.60077 1.14745 6.55319 1.25741C6.50561 1.36737 6.43655 1.46672 6.35007 1.54964L3.95007 3.94964Z" fill={iconColor}/>
              </svg>
            </button>

            {isLangDropdownOpen && (
              <div className="absolute top-full mt-2 right-0 bg-white rounded-lg shadow-lg overflow-hidden z-50 min-w-[100px]">
                <button
                  onClick={() => { setLanguage('es'); setIsLangDropdownOpen(false); }}
                  className={`w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors ${language === 'es' ? 'bg-blue-50 font-bold' : ''}`}
                >
                  🇪🇸 Español
                </button>
                <button
                  onClick={() => { setLanguage('en'); setIsLangDropdownOpen(false); }}
                  className={`w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors ${language === 'en' ? 'bg-blue-50 font-bold' : ''}`}
                >
                  🇺🇸 English
                </button>
              </div>
            )}
          </div>

          {/* Currency Selector */}
          <div className="relative">
            <button
              onClick={() => setIsCurrencyDropdownOpen(!isCurrencyDropdownOpen)}
              className="box-border flex justify-center items-center gap-[15px] m-0 p-0 cursor-pointer hover:opacity-80 transition-opacity"
            >
              <span className="box-border text-center text-base font-bold leading-[26px] uppercase m-0 p-0" style={{ color: textColor }}>
                {currency === 'MXN' ? 'MEX$' : 'USD$'}
              </span>
              <svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.95007 3.94964C3.86649 4.03325 3.76726 4.09956 3.65805 4.14481C3.54884 4.19006 3.43178 4.21335 3.31357 4.21335C3.19535 4.21335 3.0783 4.19006 2.96909 4.14481C2.85987 4.09956 2.76064 4.03325 2.67707 3.94964L0.277068 1.54964C0.190585 1.46672 0.121529 1.36737 0.0739491 1.25741C0.0263693 1.14745 0.00122424 1.0291 -1.22098e-05 0.90929C-0.00124866 0.789484 0.0214488 0.670637 0.0667488 0.559719C0.112049 0.4488 0.179041 0.348043 0.263794 0.263356C0.348548 0.178669 0.449357 0.111757 0.56031 0.0665441C0.671264 0.0213309 0.790129 -0.00127264 0.909934 5.77203e-05C1.02974 0.00138809 1.14807 0.0266266 1.25799 0.0742927C1.36792 0.121959 1.46722 0.191092 1.55007 0.277641L3.31407 2.04064L5.07707 0.27764C5.15992 0.191092 5.25922 0.121958 5.36914 0.0742923C5.47906 0.0266262 5.5974 0.00138769 5.7172 5.73e-05C5.83701 -0.00127309 5.95587 0.0213304 6.06683 0.0665436C6.17778 0.111757 6.27859 0.178669 6.36334 0.263355C6.4481 0.348042 6.51509 0.4488 6.56039 0.559718C6.60569 0.670636 6.62839 0.789483 6.62715 0.909289C6.62591 1.0291 6.60077 1.14745 6.55319 1.25741C6.50561 1.36737 6.43655 1.46672 6.35007 1.54964L3.95007 3.94964Z" fill={iconColor}/>
              </svg>
            </button>

            {isCurrencyDropdownOpen && (
              <div className="absolute top-full mt-2 right-0 bg-white rounded-lg shadow-lg overflow-hidden z-50 min-w-[120px]">
                <button
                  onClick={() => { setCurrency('MXN'); setIsCurrencyDropdownOpen(false); }}
                  className={`w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors ${currency === 'MXN' ? 'bg-blue-50 font-bold' : ''}`}
                >
                  🇲🇽 MEX$
                </button>
                <button
                  onClick={() => { setCurrency('USD'); setIsCurrencyDropdownOpen(false); }}
                  className={`w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors ${currency === 'USD' ? 'bg-blue-50 font-bold' : ''}`}
                >
                  🇺🇸 USD$
                </button>
              </div>
            )}
          </div>
        </div>
        
        <div className="box-border w-[1.5px] h-[11px] m-0 p-0 max-md:hidden" style={{ backgroundColor: iconColor }} />
        
        <div className="box-border flex items-center gap-5 m-0 p-0 max-md:gap-3">
          <button aria-label="Search">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className="max-sm:w-5 max-sm:h-5">
              <path d="M21 21L16.657 16.657M16.657 16.657C17.3998 15.9141 17.9891 15.0322 18.3912 14.0615C18.7932 13.0909 19.0002 12.0506 19.0002 11C19.0002 9.9494 18.7932 8.90908 18.3912 7.93845C17.9891 6.96782 17.3998 6.08589 16.657 5.343C15.9141 4.60011 15.0321 4.01082 14.0615 3.60877C13.0909 3.20673 12.0506 2.99979 11 2.99979C9.94936 2.99979 8.90905 3.20673 7.93842 3.60877C6.96779 4.01082 6.08585 4.60011 5.34296 5.343C3.84263 6.84333 2.99976 8.87821 2.99976 11C2.99976 13.1218 3.84263 15.1567 5.34296 16.657C6.84329 18.1573 8.87818 19.0002 11 19.0002C13.1217 19.0002 15.1566 18.1573 16.657 16.657Z" stroke={iconColor} strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
            </svg>
          </button>
          <button aria-label="User account">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className="max-sm:w-5 max-sm:h-5">
              <path d="M12 13C14.7614 13 17 10.7614 17 8C17 5.23858 14.7614 3 12 3C9.23858 3 7 5.23858 7 8C7 10.7614 9.23858 13 12 13Z" stroke={iconColor} strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
              <path d="M20 21C20 18.8783 19.1571 16.8434 17.6569 15.3431C16.1566 13.8429 14.1217 13 12 13C9.87827 13 7.84344 13.8429 6.34315 15.3431C4.84285 16.8434 4 18.8783 4 21" stroke={iconColor} strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
            </svg>
          </button>
          <button aria-label="Shopping cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className="max-sm:w-5 max-sm:h-5">
              <path d="M17 18C17.5304 18 18.0391 18.2107 18.4142 18.5858C18.7893 18.9609 19 19.4696 19 20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22C16.4696 22 15.9609 21.7893 15.5858 21.4142C15.2107 21.0391 15 20.5304 15 20C15 18.89 15.89 18 17 18ZM1 2H4.27L5.21 4H20C20.2652 4 20.5196 4.10536 20.7071 4.29289C20.8946 4.48043 21 4.73478 21 5C21 5.17 20.95 5.34 20.88 5.5L17.3 11.97C16.96 12.58 16.3 13 15.55 13H8.1L7.2 14.63L7.17 14.75C7.17 14.8163 7.19634 14.8799 7.24322 14.9268C7.29011 14.9737 7.3537 15 7.42 15H19V17H7C6.46957 17 5.96086 16.7893 5.58579 16.4142C5.21071 16.0391 5 15.5304 5 15C5 14.65 5.09 14.32 5.24 14.04L6.6 11.59L3 4H1V2ZM7 18C7.53043 18 8.03914 18.2107 8.41421 18.5858C8.78929 18.9609 9 19.4696 9 20C9 20.5304 8.78929 21.0391 8.41421 21.4142C8.03914 21.7893 7.53043 22 7 22C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20C5 18.89 5.89 18 7 18ZM16 11L18.78 6H6.14L8.5 11H16Z" fill={iconColor}/>
            </svg>
          </button>
        </div>

        {/* Mobile menu button */}
        <button 
          className="md:hidden"
          style={{ color: textColor }}
          onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          aria-label="Toggle mobile menu"
        >
          {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Mobile menu */}
      {isMobileMenuOpen && (
        <div className={`absolute top-full left-0 right-0 mt-2 backdrop-blur-md rounded-b-[10px] p-5 md:hidden shadow-lg ${isProductPage ? 'bg-white' : 'bg-[rgba(45,45,45,0.95)]'}`}>
          <nav className="flex flex-col gap-4">
            <Link 
              to="/" 
              onClick={() => setIsMobileMenuOpen(false)} 
              className="font-bold uppercase py-2 border-b border-gray-200" 
              style={{ color: textColor }}
            >
              Home
            </Link>
            <Link 
              to="/tours" 
              onClick={() => setIsMobileMenuOpen(false)} 
              className="font-bold uppercase py-2 border-b border-gray-200" 
              style={{ color: textColor }}
            >
              Tours
            </Link>
            <button 
              onClick={() => { handleActivitiesClick(); setIsMobileMenuOpen(false); }} 
              className="font-bold uppercase py-2 border-b border-gray-200 text-left" 
              style={{ color: textColor }}
            >
              Actividades
            </button>
            <Link 
              to="/about" 
              onClick={() => setIsMobileMenuOpen(false)} 
              className="font-bold uppercase py-2 border-b border-gray-200" 
              style={{ color: textColor }}
            >
              Sobre Nosotros
            </Link>
            
            {/* Language selector en mobile */}
            <div className="py-2 border-b border-gray-200">
              <span className="text-xs uppercase opacity-60 block mb-2" style={{ color: textColor }}>Idioma</span>
              <div className="flex gap-2">
                <button 
                  onClick={() => { setLanguage('es'); setIsMobileMenuOpen(false); }} 
                  className={`px-3 py-1 rounded ${language === 'es' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                >
                  🇪🇸 ES
                </button>
                <button 
                  onClick={() => { setLanguage('en'); setIsMobileMenuOpen(false); }} 
                  className={`px-3 py-1 rounded ${language === 'en' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                >
                  🇺🇸 EN
                </button>
              </div>
            </div>
            
            {/* Currency selector en mobile */}
            <div className="py-2">
              <span className="text-xs uppercase opacity-60 block mb-2" style={{ color: textColor }}>Moneda</span>
              <div className="flex gap-2">
                <button 
                  onClick={() => { setCurrency('MXN'); setIsMobileMenuOpen(false); }} 
                  className={`px-3 py-1 rounded ${currency === 'MXN' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                >
                  🇲🇽 MEX$
                </button>
                <button 
                  onClick={() => { setCurrency('USD'); setIsMobileMenuOpen(false); }} 
                  className={`px-3 py-1 rounded ${currency === 'USD' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`}
                >
                  🇺🇸 USD$
                </button>
              </div>
            </div>
          </nav>
        </div>
      )}
    </header>
  );
};

export default Header;

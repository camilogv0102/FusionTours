import React from 'react';
import Hero from '@/components/Hero';
import ToursSection from '@/components/ToursSection';
import ClientGallery from '@/components/ClientGallery';
import ActivitiesSection from '@/components/ActivitiesSection';
import AboutSection from '@/components/AboutSection';
import FAQSection from '@/components/FAQSection';
import Footer from '@/components/Footer';

const Index = () => {
  return (
    <main className="box-border flex flex-col items-center w-full bg-white m-0 p-0">
      <Hero />
      
      <ToursSection />
      
      <ActivitiesSection />
      
      <AboutSection />
      
      <ClientGallery />
      
      <FAQSection />
      
      <Footer />
    </main>
  );
};

export default Index;

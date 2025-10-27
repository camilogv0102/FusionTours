import React from 'react';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';

const FAQSection = () => {
  const faqs = [
    {
      question: "How much does Coda AI cost?",
      answer: "Unlike other tools where AI is a paid add-on, Coda AI is free for Doc Makers. Editors also receive a free trial of Coda AI. If you'd like to learn more about pricing and usage."
    },
    {
      question: "How does Coda AI use my data?",
      answer: "We take data privacy seriously and follow strict security protocols to protect your information."
    },
    {
      question: "How can I learn more about using Coda AI for work?",
      answer: "You can explore our documentation, tutorials, and contact our support team for guidance."
    },
    {
      question: "What models does Coda AI leverage?",
      answer: "Coda AI uses state-of-the-art language models to provide accurate and helpful responses."
    },
    {
      question: "Was there a Coda AI Beta?",
      answer: "Yes, we had a successful beta program that helped us refine the product before launch."
    }
  ];

  return (
    <section className="box-border flex w-full flex-col justify-center items-center gap-8 m-0 px-20 py-16 max-md:px-10 max-sm:px-5 max-sm:py-8">
      <div className="box-border flex flex-col items-center gap-8 m-0 p-0 w-full max-w-4xl">
        <h2 className="box-border text-black text-center text-[32px] font-bold leading-tight m-0 p-0 max-sm:text-[24px]">
          Preguntas Frecuentes
        </h2>
        
        <div className="box-border flex flex-col items-start m-0 p-0 w-full">
          <Accordion type="single" collapsible className="w-full">
            {faqs.map((faq, index) => (
              <AccordionItem key={index} value={`item-${index}`} className="border-t-black/20 border-t border-solid">
                <AccordionTrigger className="box-border flex w-full justify-between items-center gap-4 relative m-0 px-0 py-4 text-left hover:no-underline">
                  <span className="box-border text-[#212121] text-base font-medium leading-relaxed flex-1 m-0 p-0 max-sm:text-sm">
                    {faq.question}
                  </span>
                </AccordionTrigger>
                <AccordionContent className="box-border flex flex-col items-start gap-2 m-0 pt-0 pb-4 px-0">
                  <p className="box-border text-[#666] text-sm font-normal leading-relaxed m-0 p-0">
                    {faq.answer}
                  </p>
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </div>
      </div>
      
      <button className="box-border flex justify-center items-center gap-3 bg-[#0070C0] m-0 px-6 py-2.5 rounded-full hover:bg-[#005a9f] transition-colors">
        <span className="box-border text-white text-center text-sm font-bold uppercase m-0 p-0">
          VER TODAS
        </span>
      </button>
    </section>
  );
};

export default FAQSection;

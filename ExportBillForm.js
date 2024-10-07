import React, { useState } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

const MultiStepForm = () => {
  const [currentStep, setCurrentStep] = useState(0);
  const steps = [
    { title: 'Basic Info', fields: ['name', 'manufacturing_date', 'expiry_date', 'batch_no'] },
    { title: 'Parties', fields: ['supplier_id', 'buyer_id', 'consignee_id'] },
    { title: 'Invoice Details', fields: ['invoice_no', 'invoice_date', 'carriage'] },
    { title: 'Shipping', fields: ['place_of_receipt', 'port_of_loading', 'port_of_discharge', 'final_destination'] },
    { title: 'Product', fields: ['product_id', 'hsn_code', 'marks_and_nos', 'quantity', 'price_per_unit'] },
    { title: 'Financials', fields: ['total_unit_price', 'total_amount', 'igst_amount'] },
    { title: 'Container', fields: ['container_length', 'container_width', 'container_height', 'container_weight_net', 'container_weight_gross', 'container_other'] },
    { title: 'Bank Details', fields: ['bank_name', 'ifsc_code', 'bank_account_no'] },
    { title: 'Additional Info', fields: ['gstin', 'vat_tin', 'iec_code', 'end_user_code', 'destination', 'drug_license_no'] },
  ];

  const nextStep = () => setCurrentStep((prev) => Math.min(prev + 1, steps.length - 1));
  const prevStep = () => setCurrentStep((prev) => Math.max(prev - 1, 0));

  return (
    <div className="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
      <h1 className="text-3xl font-bold text-center mb-8 text-blue-600">Export Bill Form</h1>
      <div className="mb-8">
        <div className="flex justify-between items-center">
          {steps.map((step, index) => (
            <div key={index} className={`flex-1 ${index < steps.length - 1 ? 'border-b-2' : ''} ${index <= currentStep ? 'border-blue-500' : 'border-gray-300'}`}>
              <div className={`w-8 h-8 mx-auto rounded-full text-lg flex items-center justify-center ${index <= currentStep ? 'bg-blue-500 text-white' : 'bg-gray-300'}`}>
                {index + 1}
              </div>
            </div>
          ))}
        </div>
        <div className="flex justify-between mt-2">
          {steps.map((step, index) => (
            <div key={index} className="text-center">
              <div className={`text-xs ${index <= currentStep ? 'text-blue-500' : 'text-gray-500'}`}>{step.title}</div>
            </div>
          ))}
        </div>
      </div>
      <form>
        {steps[currentStep].fields.map((field) => (
          <div key={field} className="mb-4">
            <label htmlFor={field} className="block text-sm font-medium text-gray-700 mb-1">{field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</label>
            <input type="text" id={field} name={field} className="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
          </div>
        ))}
        <div className="flex justify-between mt-8">
          <button type="button" onClick={prevStep} disabled={currentStep === 0} className="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
            <ChevronLeft className="mr-2" size={20} />
            Previous
          </button>
          {currentStep < steps.length - 1 ? (
            <button type="button" onClick={nextStep} className="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
              Next
              <ChevronRight className="ml-2" size={20} />
            </button>
          ) : (
            <button type="submit" className="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
              Submit
            </button>
          )}
        </div>
      </form>
    </div>
  );
};

export default MultiStepForm;
<?php

namespace Database\Seeders;

use App\Models\ApplicationStep;
use Illuminate\Database\Seeder;

class ApplicationStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Stage 1: Eligibility (ID: 1)
        $eligibilitySteps = [
            [
                'stage_id' => 1,
                'name' => 'Check Eligibility Criteria',
                'description' => 'Determine if you meet the eligibility criteria for the Global Talent Visa',
                'instructions' => 'Review the eligibility criteria and confirm if you meet them.',
                'order' => 1,
                'form_type' => 'checklist', // Example: a series of yes/no questions
                'form_schema' => json_encode([
                    'questions' => [
                        ['id' => 'is_leader_or_potential_leader', 'text' => 'Are you a leader or potential leader in your field (qualifying fields: academia or research, arts and culture, digital technology)?', 'type' => 'boolean'],
                        ['id' => 'meets_endorsement_criteria', 'text' => 'Do you meet the specific endorsement criteria for your field?', 'type' => 'boolean'],
                    ]
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 1,
                'name' => 'Understand the Process',
                'description' => 'Learn about the Global Talent Visa application process.',
                'instructions' => 'Familiarize yourself with the two-stage application process: endorsement and visa application.',
                'order' => 2,
                'form_type' => 'information', // Just informational text
                'form_schema' => json_encode([
                    'content' => 'The Global Talent visa application is a two-stage process. First, you must apply for an endorsement to prove you are a leader or potential leader. Second, you apply for the visa itself.'
                ]),
                'is_required' => true,
            ],
        ];

        // Stage 2: Endorsement (ID: 2)
        $endorsementSteps = [
            [
                'stage_id' => 2,
                'name' => 'Choose Endorsing Body',
                'description' => 'Identify the correct endorsing body for your field.',
                'instructions' => 'Select the endorsing body relevant to your field: The Royal Society (science and medicine), The Royal Academy of Engineering (engineering), The British Academy (humanities and social sciences), Tech Nation (digital technology), Arts Council England (arts and culture, fashion, architecture, film and television).',
                'order' => 1,
                'form_type' => 'select',
                'form_schema' => json_encode([
                    'label' => 'Select your endorsing body:',
                    'options' => [
                        'tech_nation' => 'Tech Nation (Digital Technology)',
                        'royal_society' => 'The Royal Society (Science and Medicine)',
                        'royal_academy_engineering' => 'The Royal Academy of Engineering (Engineering)',
                        'british_academy' => 'The British Academy (Humanities and Social Sciences)',
                        'arts_council_england' => 'Arts Council England (Arts, Culture, Fashion, Architecture, Film & Television)',
                    ]
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 2,
                'name' => 'Prepare Endorsement Application Documents',
                'description' => 'Gather all necessary documents for your endorsement application.',
                'instructions' => 'This typically includes a CV, personal statement, letters of recommendation, and evidence of your achievements. Check specific requirements for your chosen endorsing body.',
                'order' => 2,
                'form_type' => 'document_checklist', // User confirms they have these documents
                'form_schema' => json_encode([
                    'items' => [
                        ['id' => 'cv', 'label' => 'Curriculum Vitae (CV)', 'required' => true],
                        ['id' => 'personal_statement', 'label' => 'Personal Statement (max 1000 words)', 'required' => true],
                        ['id' => 'letters_of_recommendation', 'label' => 'Letters of Recommendation (usually 3)', 'required' => true],
                        ['id' => 'evidence_achievements', 'label' => 'Evidence of Achievements/Contributions', 'required' => true],
                    ]
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 2,
                'name' => 'Submit Endorsement Application Online',
                'description' => 'Complete and submit the endorsement application form via the Home Office website.',
                'instructions' => 'You will need to create an account and fill in the online application form. You will upload your documents as part of this process.',
                'order' => 3,
                'form_type' => 'document_upload',
                'form_schema' => json_encode([
                    'fields' => [
                        ['id' => 'cv_upload', 'label' => 'Upload CV', 'type' => 'file', 'accept' => '.pdf,.doc,.docx', 'required' => true],
                        ['id' => 'personal_statement_upload', 'label' => 'Upload Personal Statement', 'type' => 'file', 'accept' => '.pdf,.doc,.docx', 'required' => true],
                        ['id' => 'recommendation_letter_1_upload', 'label' => 'Upload Recommendation Letter 1', 'type' => 'file', 'accept' => '.pdf,.doc,.docx', 'required' => true],
                        ['id' => 'recommendation_letter_2_upload', 'label' => 'Upload Recommendation Letter 2', 'type' => 'file', 'accept' => '.pdf,.doc,.docx', 'required' => true],
                        ['id' => 'recommendation_letter_3_upload', 'label' => 'Upload Recommendation Letter 3', 'type' => 'file', 'accept' => '.pdf,.doc,.docx', 'required' => true],
                        ['id' => 'evidence_achievements_upload', 'label' => 'Upload Evidence of Achievements (up to 10 pieces)', 'type' => 'file', 'accept' => '.pdf,.doc,.docx,.jpg,.png', 'multiple' => true, 'max_files' => 10, 'required' => true],
                    ]
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 2,
                'name' => 'Pay Endorsement Fee',
                'description' => 'Pay the fee for the endorsement application.',
                'instructions' => 'The fee is £456 (as of current information, subject to change). Payment is made online.',
                'order' => 4,
                'form_type' => 'payment_confirmation', // User confirms payment or enters reference
                'form_schema' => json_encode([
                    'fee_amount' => 456,
                    'currency' => 'GBP',
                    'confirmation_label' => 'I have paid the endorsement fee.',
                    'reference_field_label' => 'Payment Reference (Optional)'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 2,
                'name' => 'Wait for Endorsement Decision',
                'description' => 'The endorsing body will review your application.',
                'instructions' => 'Processing times vary. Tech Nation aims for 3 weeks. Other bodies may take up to 8 weeks. You will be notified of the decision by email.',
                'order' => 5,
                'form_type' => 'status_update', // User can update status once decision received
                'form_schema' => json_encode([
                    'options' => [
                        'pending' => 'Awaiting Decision',
                        'endorsed' => 'Endorsement Approved',
                        'not_endorsed' => 'Endorsement Rejected',
                    ],
                    'date_field_label' => 'Date of Decision'
                ]),
                'is_required' => true,
            ],
        ];

        // Stage 3: Visa Application (ID: 3)
        $visaSteps = [
            [
                'stage_id' => 3,
                'name' => 'Receive Endorsement Letter',
                'description' => 'If endorsed, you will receive an endorsement letter.',
                'instructions' => 'This letter is crucial for your visa application. It is valid for 3 months from the date of issue.',
                'order' => 1,
                'form_type' => 'document_upload',
                'form_schema' => json_encode([
                    'fields' => [
                        ['id' => 'endorsement_letter_upload', 'label' => 'Upload Endorsement Letter', 'type' => 'file', 'accept' => '.pdf', 'required' => true],
                    ]
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 3,
                'name' => 'Complete Online Visa Application Form',
                'description' => 'Fill in the Global Talent visa application form on GOV.UK.',
                'instructions' => 'You must apply within 3 months of receiving your endorsement. You will need your endorsement letter, passport, and other personal information.',
                'order' => 2,
                'form_type' => 'external_link_confirmation', // Link to GOV.UK, user confirms completion
                'form_schema' => json_encode([
                    'link_url' => 'https://www.gov.uk/global-talent/apply-for-your-visa',
                    'link_text' => 'Go to GOV.UK Visa Application',
                    'confirmation_label' => 'I have completed the online visa application form.'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 3,
                'name' => 'Pay Visa Application Fee and Immigration Health Surcharge (IHS)',
                'description' => 'Pay the required fees for the visa.',
                'instructions' => 'The visa fee is £192 (as of current information, subject to change). The IHS fee depends on the length of your visa. Both are paid online.',
                'order' => 3,
                'form_type' => 'payment_confirmation',
                'form_schema' => json_encode([
                    'fees' => [
                        ['id' => 'visa_fee', 'label' => 'Visa Application Fee', 'amount' => 192, 'currency' => 'GBP'],
                        ['id' => 'ihs_fee', 'label' => 'Immigration Health Surcharge (IHS)', 'amount_variable' => true, 'currency' => 'GBP'],
                    ],
                    'confirmation_label' => 'I have paid the visa fee and IHS.',
                    'reference_field_label' => 'Payment Reference (Optional)'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 3,
                'name' => 'Book and Attend Biometric Appointment',
                'description' => 'Provide your fingerprints and a photograph at a visa application centre.',
                'instructions' => 'You will be prompted to book an appointment after submitting your online application and paying the fees. You must bring your passport and other required documents.',
                'order' => 4,
                'form_type' => 'appointment_confirmation',
                'form_schema' => json_encode([
                    'appointment_type' => 'Biometric Appointment',
                    'date_field_label' => 'Date of Appointment',
                    'location_field_label' => 'Location of Appointment Centre',
                    'confirmation_label' => 'I have attended my biometric appointment.'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 3,
                'name' => 'Upload Supporting Documents (if not already done)',
                'description' => 'Ensure all supporting documents are submitted.',
                'instructions' => 'This includes your passport, endorsement letter, TB test certificate (if required), and any other documents requested. This may be done online or at the visa application centre.',
                'order' => 5,
                'form_type' => 'document_checklist_confirmation',
                'form_schema' => json_encode([
                    'items' => [
                        ['id' => 'passport_submitted', 'label' => 'Passport copy submitted/uploaded', 'required' => true],
                        ['id' => 'endorsement_letter_submitted', 'label' => 'Endorsement letter submitted/uploaded', 'required' => true],
                        ['id' => 'tb_test_submitted', 'label' => 'TB test certificate submitted/uploaded (if applicable)', 'required' => false],
                    ],
                    'confirmation_label' => 'All required documents have been submitted.'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 3,
                'name' => 'Wait for Visa Decision',
                'description' => 'The Home Office will process your visa application.',
                'instructions' => 'Processing times: 3 weeks if applying from outside the UK, 8 weeks if applying from inside the UK (standard service). Priority services may be available.',
                'order' => 6,
                'form_type' => 'status_update',
                'form_schema' => json_encode([
                    'options' => [
                        'pending' => 'Awaiting Decision',
                        'approved' => 'Visa Approved',
                        'rejected' => 'Visa Rejected',
                    ],
                    'date_field_label' => 'Date of Decision'
                ]),
                'is_required' => true,
            ],
        ];

        // Stage 4: After Decision (ID: 4)
        $afterDecisionSteps = [
            [
                'stage_id' => 4,
                'name' => 'If Visa Approved: Receive Visa/BRP',
                'description' => 'Understand how you will receive your visa or Biometric Residence Permit (BRP).',
                'instructions' => 'If outside the UK, you\'ll get a vignette (sticker) in your passport to enter the UK. You then collect your BRP within 10 days of arrival or by the vignette expiry date, whichever is later. If inside the UK, your BRP will be sent to your address.',
                'order' => 1,
                'form_type' => 'information_acknowledgement',
                'form_schema' => json_encode([
                    'content' => 'Ensure you understand the process for receiving your visa vignette and collecting your Biometric Residence Permit (BRP) upon arrival, or receiving your BRP if you applied from within the UK.',
                    'acknowledgement_label' => 'I understand how to receive my visa/BRP.'
                ]),
                'is_required' => true,
            ],
            [
                'stage_id' => 4,
                'name' => 'If Visa Approved: Travel to the UK (if applicable)',
                'description' => 'Plan your travel to the UK.',
                'instructions' => 'You must travel to the UK before your entry vignette expires.',
                'order' => 2,
                'form_type' => 'date_input',
                'form_schema' => json_encode([
                    'label' => 'Planned Date of Arrival in the UK',
                    'required' => false // Only if visa approved and applying from outside
                ]),
                'is_required' => false,
            ],
            [
                'stage_id' => 4,
                'name' => 'If Visa Approved: Register with Police (if required)',
                'description' => 'Check if you need to register with the police.',
                'instructions' => 'Some nationalities are required to register with the police within 7 days of arrival in the UK. Check your visa decision letter.',
                'order' => 3,
                'form_type' => 'checklist',
                'form_schema' => json_encode([
                    'questions' => [
                        ['id' => 'police_registration_required', 'text' => 'Does your visa decision letter state you need to register with the police?', 'type' => 'boolean'],
                        ['id' => 'police_registration_completed', 'text' => 'If yes, have you completed police registration?', 'type' => 'boolean', 'depends_on' => 'police_registration_required', 'depends_on_value' => true],
                    ]
                ]),
                'is_required' => false,
            ],
            [
                'stage_id' => 4,
                'name' => 'If Visa Rejected: Understand Reasons and Options',
                'description' => 'Review the refusal letter and understand your options.',
                'instructions' => 'The refusal letter will explain why your application was rejected. You may be able to apply for an administrative review or submit a new application.',
                'order' => 4,
                'form_type' => 'information_acknowledgement',
                'form_schema' => json_encode([
                    'content' => 'If your visa application is rejected, carefully read the refusal letter. It will outline the reasons for refusal and any options you may have, such as an administrative review or making a new application.',
                    'acknowledgement_label' => 'I understand the next steps if my visa is rejected.'
                ]),
                'is_required' => false, // Only relevant if visa rejected
            ],
        ];

        foreach (array_merge($eligibilitySteps, $endorsementSteps, $visaSteps, $afterDecisionSteps) as $step) {
            ApplicationStep::create($step);
        }
    }
} 
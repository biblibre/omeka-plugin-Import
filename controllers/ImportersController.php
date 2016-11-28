<?php

class Import_ImportersController extends Zend_Controller_Action
{
    public function addAction()
    {
        $form = new Import_Form_ImporterForm();
        $importer = new Import_Importer;

        if ($this->getRequest()->isPost()) {
            $this->validateAndSave($form, $importer);
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $db = $this->getHelper('db');

        $importer = $db->getTable('Import_Importer')->find($this->getParam('id'));
        $form = new Import_Form_ImporterForm(array(
            'importer' => $importer
        ));

        if ($this->getRequest()->isPost()) {
            $this->validateAndSave($form, $importer);
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $db = $this->getHelper('db');

        $importer = $db->getTable('Import_Importer')->find($this->getParam('id'));
        $form = new Import_Form_ImporterDeleteForm(array(
            'importer' => $importer
        ));

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                try {
                    $importer->delete();
                    $this->flash(__('Importer successfully deleted'));
                    $this->redirect('import');
                } catch (Exception $e) {
                    $this->flash(sprintf('Deletion of importer failed : %s', $e->getMessage()), 'error');
                }
            }
        }

        $this->view->importer = $importer;
        $this->view->form = $form;
    }

    public function configureReaderAction()
    {
        $db = $this->getHelper('db');

        $importer = $db->getTable('Import_Importer')->find($this->getParam('id'));
        $reader = $importer->getReader();

        $form = $reader->getConfigForm();
        $form->addElement('submit', 'submit', array(
            'label' => __('Save'),
        ));

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $reader->handleConfigForm($form);
                $importer->setReaderConfig($reader->getConfig());
                $importer->save();
                $this->flash(__('Reader configuration saved'));
                $this->redirect('import');
            } else {
                $this->flash(__('Form is invalid'), 'error');
            }
        }

        $this->view->form = $form;
    }

    public function configureProcessorAction()
    {
        $db = $this->getHelper('db');

        $importer = $db->getTable('Import_Importer')->find($this->getParam('id'));
        $processor = $importer->getProcessor();

        $form = $processor->getConfigForm();
        $form->addElement('submit', 'submit', array(
            'label' => __('Save'),
        ));

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $processor->handleConfigForm($form);
                $importer->setProcessorConfig($processor->getConfig());
                $importer->save();
                $this->flash(__('Processor configuration saved'));
                $this->redirect('import');
            } else {
                $this->flash(__('Form is invalid'), 'error');
            }
        }

        $this->view->form = $form;
    }

    public function startAction()
    {
        $db = $this->getHelper('db');

        $importer = $db->getTable('Import_Importer')->find($this->getParam('id'));
        $reader = $importer->getReader();
        $processor = $importer->getProcessor();
        $processor->setReader($reader);

        $session = new Zend_Session_Namespace('ImporterStartForm');
        if (!$this->getRequest()->isPost()) {
            $session->unsetAll();
        }
        if (isset($session->reader)) {
            $reader->setParams($session->reader);
        }
        if (isset($session->processor)) {
            $processor->setParams($session->processor);
        }

        $formsCallbacks = $this->getStartFormsCallbacks($importer);
        $formCallback = reset($formsCallbacks);

        if ($this->getRequest()->isPost()) {
            $currentForm = $this->getRequest()->getPost('current_form');
            $form = call_user_func($formsCallbacks[$currentForm]);
            if ($form->isValid($_POST)) {
                $values = $form->getValues();
                $session->{$currentForm} = $values;
                if ($currentForm == 'reader') {
                    $reader->handleParamsForm($form);
                    $session->reader = $reader->getParams();
                    $formCallback = isset($formsCallbacks['processor']) ? $formsCallbacks['processor'] : $formsCallbacks['start'];
                } elseif ($currentForm == 'processor') {
                    $processor->handleParamsForm($form);
                    $session->processor = $processor->getParams();
                    $formCallback = $formsCallbacks['start'];
                } elseif ($currentForm == 'start') {
                    $import = new Import_Import;
                    $import->importer_id = $importer->id;
                    if ($reader instanceof Import_Parametrizable) {
                        $import->setReaderParams($reader->getParams());
                    }
                    if ($processor instanceof Import_Parametrizable) {
                        $import->setProcessorParams($processor->getParams());
                    }
                    $import->status = 'queued';
                    $import->save();
                    $session->unsetAll();

                    $jobDispatcher = Zend_Registry::get('job_dispatcher');
                    $jobDispatcher->setQueueName('import_imports');
                    try {
                        $jobDispatcher->sendLongRunning('Import_Job_Import', array(
                            'importId' => $import->id,
                        ));
                        $this->flash('Import started');
                    } catch (Exception $e) {
                        $import->status = 'error';
                        $this->flash('Import start failed', 'error');
                    }

                    $this->redirect('import');
                }
            } else {
                $this->flash(__('Form is invalid'), 'error');
                foreach ($form->getMessages() as $messages) {
                    foreach ($messages as $message) {
                        $this->flash($message, 'error');
                    }
                }
            }
        }

        $form = call_user_func($formCallback);
        $this->view->form = $form;
    }

    protected function getStartFormsCallbacks($importer)
    {
        $formsCallbacks = array();

        $reader = $importer->getReader();
        if ($reader instanceof Import_Parametrizable) {
            $formsCallbacks['reader'] = function() use($reader) {
                $readerForm = $reader->getParamsForm();
                $readerForm->addElement('hidden', 'current_form', array(
                    'value' => 'reader',
                ));
                $readerForm->addElement('submit', 'submit', array(
                    'label' => __('Continue'),
                ));
                $readerForm->addDisplayGroup(array('submit'), 'reader_submit');

                return $readerForm;
            };
        }

        $processor = $importer->getProcessor();
        $processor->setReader($reader);
        if ($processor instanceof Import_Parametrizable) {
            $formsCallbacks['processor'] = function() use($processor) {
                $processorForm = $processor->getParamsForm();
                $processorForm->addElement('hidden', 'current_form', array(
                    'value' => 'processor',
                ));
                $processorForm->addElement('submit', 'submit', array(
                    'label' => __('Continue'),
                ));
                $processorForm->addDisplayGroup(array('submit'), 'processor_submit');

                return $processorForm;
            };
        }

        $formsCallbacks['start'] = function() {
            $startForm = new Import_Form_ImporterStartForm();
            $startForm->addElement('hidden', 'current_form', array(
                'value' => 'start',
            ));

            return $startForm;
        };

        return $formsCallbacks;
    }

    protected function flash($message, $namespace = 'success')
    {
        $flashMessenger = $this->getHelper('FlashMessenger');
        $flashMessenger->addMessage($message, $namespace);
    }

    protected function validateAndSave(Zend_Form $form, Import_Importer $importer)
    {
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $importer->setPostData($values);
            try {
                $importer->save();
                $this->flash(__('Importer successfully saved'));
                $this->redirect('import');
            } catch (Exception $e) {
                $this->flash(sprintf('Save of importer failed : %s', $e->getMessage()));
            }
        } else {
            $this->flash(__('Form is invalid'));
            foreach ($form->getErrorMessages() as $message) {
                $this->flash($message, 'error');
            }
        }
    }
}

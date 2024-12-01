@props(['evento', 'user'])

<div class="fixed inset-0 z-10 flex items-center justify-center bg-gray-500/75" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Modal panel -->
  <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 justify-center">
      <div class="sm:flex sm:items-start justify-center">
        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
          <h3 class="text-base font-semibold text-gray-900" id="modal-title">Inscrição no evento {{ $evento['descricao'] }}</h3>
        </div>
      </div>
    </div>
    <div class="mt-3 bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6" style="padding-bottom: 5px">
      <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" style="align-items: center" onclick="inscreverEvento( {{ $user['id'] }}, {{ $evento['id'] }} )">Inscrever</button>
      <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" 
              onclick="closeModal({{$evento['id']}})">Cancelar</button>
    </div>
  </div>
</div>

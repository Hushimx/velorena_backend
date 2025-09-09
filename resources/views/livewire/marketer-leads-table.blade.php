<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 rounded-2xl shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 ml-3 text-xl"></i>
                <span class="font-semibold">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-8 bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search ml-2"></i>
                    {{ __('marketer.search') }}
                </label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ __('marketer.search_leads') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
            </div>
            <div>
                <label for="statusFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-filter ml-2"></i>
                    {{ __('marketer.status') }}
                </label>
                <select wire:model.live="statusFilter" wire:key="status-filter"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <option value="">{{ __('marketer.all_statuses') }}</option>
                    <option value="new">{{ __('marketer.new') }}</option>
                    <option value="contacted">{{ __('marketer.contacted') }}</option>
                    <option value="qualified">{{ __('marketer.qualified') }}</option>
                    <option value="proposal_sent">{{ __('marketer.proposal_sent') }}</option>
                    <option value="negotiation">{{ __('marketer.negotiation') }}</option>
                    <option value="closed_won">{{ __('marketer.closed_won') }}</option>
                    <option value="closed_lost">{{ __('marketer.closed_lost') }}</option>
                </select>
            </div>
            <div>
                <label for="priorityFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-flag ml-2"></i>
                    {{ __('marketer.priority') }}
                </label>
                <select wire:model.live="priorityFilter" wire:key="priority-filter"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <option value="">{{ __('marketer.all_priorities') }}</option>
                    <option value="high">{{ __('marketer.high') }}</option>
                    <option value="medium">{{ __('marketer.medium') }}</option>
                    <option value="low">{{ __('marketer.low') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-building ml-2"></i>
                            {{ __('marketer.company') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user ml-2"></i>
                            {{ __('marketer.contact_person') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-envelope ml-2"></i>
                            {{ __('marketer.email') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle ml-2"></i>
                            {{ __('marketer.status') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-flag ml-2"></i>
                            {{ __('marketer.priority') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-phone ml-2"></i>
                            {{ __('marketer.last_contact') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar ml-2"></i>
                            {{ __('marketer.next_follow_up') }}
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cogs ml-2"></i>
                            {{ __('marketer.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center ml-3">
                                        <i class="fas fa-building text-white"></i>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $lead->company_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lead->contact_person }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <i class="fas fa-envelope text-gray-400 ml-2"></i>
                                    {{ $lead->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($lead->status == 'new') bg-gray-100 text-gray-800 border border-gray-200
                                    @elseif($lead->status == 'contacted') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($lead->status == 'qualified') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif($lead->status == 'proposal_sent') bg-purple-100 text-purple-800 border border-purple-200
                                    @elseif($lead->status == 'negotiation') bg-orange-100 text-orange-800 border border-orange-200
                                    @elseif($lead->status == 'closed_won') bg-green-100 text-green-800 border border-green-200
                                    @else bg-red-100 text-red-800 border border-red-200
                                    @endif">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($lead->priority == 'high') bg-red-100 text-red-800 border border-red-200
                                    @elseif($lead->priority == 'medium') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @else bg-green-100 text-green-800 border border-green-200
                                    @endif">
                                    {{ ucfirst($lead->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-gray-400 ml-2"></i>
                                    {{ $lead->last_contact_date ? $lead->last_contact_date->format('Y-m-d') : __('marketer.no_contact') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-gray-400 ml-2"></i>
                                    {{ $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d') : __('marketer.not_set') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('marketer.leads.show', $lead) }}"
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center hover:shadow-lg transition-all duration-300 hover:scale-110">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('marketer.leads.edit', $lead) }}"
                                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl flex items-center justify-center hover:shadow-lg transition-all duration-300 hover:scale-110">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-6">
                                        <i class="fas fa-inbox text-4xl text-gray-500"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('marketer.no_leads_assigned_to_you') }}</h3>
                                    <p class="text-gray-500">{{ __('marketer.leads_will_be_assigned_by_admin') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
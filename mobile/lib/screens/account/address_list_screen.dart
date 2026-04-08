import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../models/address.dart';
import '../../services/address_service.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/common/animated_empty_state.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/animations/blur_fade.dart';
import 'address_form_screen.dart';

class AddressListScreen extends StatefulWidget {
  const AddressListScreen({super.key});

  @override
  State<AddressListScreen> createState() => _AddressListScreenState();
}

class _AddressListScreenState extends State<AddressListScreen> {
  final AddressService _addressService = AddressService();
  List<Address> _addresses = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchAddresses();
  }

  Future<void> _fetchAddresses() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final addresses = await _addressService.getAddresses();

      setState(() {
        _addresses = addresses;
        _isLoading = false;
      });
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = e.toString().replaceAll('ApiException: ', '');
          _isLoading = false;
        });
      }
    }
  }

  void _navigateToAddAddress() async {
    final result = await Navigator.push<bool>(
      context,
      MaterialPageRoute<bool>(builder: (context) => const AddressFormScreen()),
    );

    if (result == true) {
      _fetchAddresses();
    }
  }

  void _navigateToEditAddress(Address address) async {
    final result = await Navigator.push<bool>(
      context,
      MaterialPageRoute<bool>(
        builder: (context) => AddressFormScreen(address: address),
      ),
    );

    if (result == true) {
      _fetchAddresses();
    }
  }

  void _deleteAddress(Address address) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text(
          'Hapus Alamat?',
          style: TextStyle(fontWeight: FontWeight.w700),
        ),
        content: const Text('Apakah Anda yakin ingin menghapus alamat ini?'),
        shape: RoundedRectangleBorder(borderRadius: AppTheme.radius22),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.error,
              foregroundColor: Colors.white,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
            ),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirmed == true && mounted) {
      setState(() {
        _isLoading = true;
      });
      try {
        await _addressService.deleteAddress(address.id);
        if (mounted) {
          _fetchAddresses();
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Alamat berhasil dihapus')),
          );
        }
      } catch (e) {
        if (mounted) {
          setState(() {
            _isLoading = false;
          });
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(SnackBar(content: Text('Gagal menghapus alamat: $e')));
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Alamat Pengiriman',
      subtitle: 'Kelola alamat untuk pengiriman pesanan',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: _buildBody(),
      floatingActionButton: _addresses.isEmpty
          ? null
          : Padding(
              padding: EdgeInsets.only(
                bottom: ResponsiveHelper.horizontalPadding(context),
                right: ResponsiveHelper.horizontalPadding(context),
              ),
              child: FloatingActionButton.extended(
                onPressed: _isLoading ? null : _navigateToAddAddress,
                backgroundColor: AppTheme.primary,
                foregroundColor: Colors.white,
                elevation: 2,
                icon: const Icon(Icons.add, size: 20),
                label: const Text(
                  'Tambah Alamat',
                  style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
                ),
              ),
            ),
    );
  }

  Widget _buildBody() {
    if (_isLoading) {
      return const AddressListSkeleton();
    }

    if (_error != null) {
      return Center(
        child: FadeInUp(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: EdgeInsets.all(
                  ResponsiveHelper.horizontalPadding(context),
                ),
                decoration: BoxDecoration(
                  color: AppTheme.errorLight,
                  borderRadius: AppTheme.radius22,
                ),
                child: const Icon(
                  Icons.error_outline,
                  size: 48,
                  color: AppTheme.error,
                ),
              ),
              const SizedBox(height: 16),
              Text(
                _error!,
                textAlign: TextAlign.center,
                style: const TextStyle(color: AppTheme.onSurfaceVariant),
              ),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                onPressed: _fetchAddresses,
                icon: const Icon(Icons.refresh),
                label: const Text('Coba Lagi'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.accent,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: AppTheme.radius16,
                  ),
                ),
              ),
            ],
          ),
        ),
      );
    }

    if (_addresses.isEmpty) {
      return AnimatedEmptyState(
        icon: Icons.location_off_outlined,
        title: 'Belum Ada Alamat',
        subtitle:
            'Tambahkan alamat pengiriman untuk memudahkan proses checkout.',
        buttonText: 'Tambah Alamat',
        onAction: _navigateToAddAddress,
      );
    }

    return ListView.builder(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      itemCount: _addresses.length,
      itemBuilder: (context, index) {
        final address = _addresses[index];
        return FadeInUp(
          delay: Duration(milliseconds: index * 50),
          child: _buildAddressCard(address),
        );
      },
    );
  }

  Widget _buildAddressCard(Address address) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: AppTheme.radius16,
        border: Border.all(
          color: address.isPrimary
              ? AppTheme.primary.withValues(alpha: 0.3)
              : AppTheme.outline,
          width: address.isPrimary ? 2 : 1,
        ),
        boxShadow: [
          BoxShadow(
            color: AppTheme.onSurface.withValues(alpha: 0.04),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: AppTheme.radius16,
        child: Material(
          color: Colors.transparent,
          child: InkWell(
            onTap: () => _navigateToEditAddress(address),
            child: Padding(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Header row with label and actions
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      // Label badge
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          color: address.isPrimary
                              ? AppTheme.primary
                              : AppTheme.accent.withValues(alpha: 0.1),
                          borderRadius: AppTheme.radius8,
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            if (address.isPrimary) ...[
                              const Icon(
                                Icons.star,
                                size: 14,
                                color: Colors.white,
                              ),
                              const SizedBox(width: 4),
                            ],
                            Text(
                              address.isPrimary ? 'Utama' : address.label,
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w700,
                                color: address.isPrimary
                                    ? Colors.white
                                    : AppTheme.accent,
                              ),
                            ),
                          ],
                        ),
                      ),
                      // Action buttons
                      Row(
                        children: [
                          IconButton(
                            onPressed: () => _navigateToEditAddress(address),
                            icon: Icon(
                              Icons.edit_outlined,
                              size: 20,
                              color: AppTheme.onSurfaceVariant,
                            ),
                            tooltip: 'Edit',
                            padding: EdgeInsets.zero,
                            constraints: const BoxConstraints(
                              minWidth: 36,
                              minHeight: 36,
                            ),
                          ),
                          IconButton(
                            onPressed: () => _deleteAddress(address),
                            icon: Icon(
                              Icons.delete_outline,
                              size: 20,
                              color: AppTheme.error,
                            ),
                            tooltip: 'Hapus',
                            padding: EdgeInsets.zero,
                            constraints: const BoxConstraints(
                              minWidth: 36,
                              minHeight: 36,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  // Recipient info
                  Text(
                    address.recipientName,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: AppTheme.onSurface,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    address.phone,
                    style: TextStyle(
                      fontSize: 14,
                      color: AppTheme.onSurfaceVariant,
                    ),
                  ),
                  const SizedBox(height: 12),
                  // Address details with icon
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Icon(
                        Icons.location_on_outlined,
                        size: 16,
                        color: AppTheme.onSurfaceMuted,
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              address.addressLine1,
                              style: TextStyle(
                                fontSize: 14,
                                color: AppTheme.onSurface,
                                height: 1.5,
                              ),
                            ),
                            if (address.addressLine2 != null &&
                                address.addressLine2!.isNotEmpty)
                              Text(
                                address.addressLine2!,
                                style: TextStyle(
                                  fontSize: 14,
                                  color: AppTheme.onSurface,
                                  height: 1.5,
                                ),
                              ),
                            const SizedBox(height: 4),
                            Text(
                              '${address.city}, ${address.province} ${address.postalCode}',
                              style: TextStyle(
                                fontSize: 14,
                                color: AppTheme.onSurface,
                                height: 1.5,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

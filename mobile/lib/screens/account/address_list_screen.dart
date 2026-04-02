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
    final result = await Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const AddressFormScreen()),
    );

    if (result == true) {
      _fetchAddresses();
    }
  }

  void _navigateToEditAddress(Address address) async {
    final result = await Navigator.push(
      context,
      MaterialPageRoute(
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
      title: 'Daftar Alamat',
      subtitle: 'Kelola alamat pengiriman untuk memudahkan proses checkout.',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back),
        tooltip: 'Back',
        onPressed: () => Navigator.pop(context),
      ),
      bodyPadding: EdgeInsets.zero,
      body: _buildBody(),
      floatingActionButton: Padding(
        padding: EdgeInsets.only(
          bottom: ResponsiveHelper.horizontalPadding(context),
        ),
        child: FloatingActionButton.extended(
          onPressed: _isLoading ? null : _navigateToAddAddress,
          backgroundColor: AppTheme.accent,
          foregroundColor: Colors.white,
          icon: const Icon(Icons.add),
          label: const Text(
            'Tambah Alamat Baru',
            style: TextStyle(fontWeight: FontWeight.bold),
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
        title: 'Belum ada alamat',
        subtitle: 'Tambahkan alamat untuk keperluan pengiriman.',
        buttonText: 'Tambah Alamat Baru',
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
      margin: const EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: AppTheme.radius16,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Padding(
        padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Text(
                      address.label,
                      style: const TextStyle(
                        fontWeight: FontWeight.w700,
                        fontSize: 14,
                        color: AppTheme.slate500,
                      ),
                    ),
                    if (address.isPrimary) ...[
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 8,
                          vertical: 2,
                        ),
                        decoration: BoxDecoration(
                          color: AppTheme.primary.withValues(alpha: 0.1),
                          borderRadius: AppTheme.radius8,
                        ),
                        child: const Text(
                          'Utama',
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w700,
                            color: AppTheme.primary,
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
                PopupMenuButton<String>(
                  onSelected: (value) {
                    if (value == 'edit') {
                      _navigateToEditAddress(address);
                    } else if (value == 'delete') {
                      _deleteAddress(address);
                    }
                  },
                  itemBuilder: (context) => [
                    const PopupMenuItem(
                      value: 'edit',
                      child: Row(
                        children: [
                          Icon(Icons.edit, size: 20),
                          SizedBox(width: 8),
                          Text('Edit'),
                        ],
                      ),
                    ),
                    const PopupMenuItem(
                      value: 'delete',
                      child: Row(
                        children: [
                          Icon(Icons.delete, size: 20, color: AppTheme.error),
                          SizedBox(width: 8),
                          Text(
                            'Hapus',
                            style: TextStyle(color: AppTheme.error),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              address.recipientName,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: AppTheme.primary,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              address.phone,
              style: const TextStyle(fontSize: 14, color: AppTheme.slate700),
            ),
            const SizedBox(height: 8),
            Text(
              address.addressLine1,
              style: const TextStyle(fontSize: 14, color: AppTheme.slate700),
            ),
            if (address.addressLine2 != null &&
                address.addressLine2!.isNotEmpty)
              Text(
                address.addressLine2!,
                style: const TextStyle(fontSize: 14, color: AppTheme.slate700),
              ),
            const SizedBox(height: 4),
            Text(
              '${address.city}, ${address.province}',
              style: const TextStyle(fontSize: 14, color: AppTheme.slate700),
            ),
            Text(
              address.postalCode,
              style: const TextStyle(fontSize: 14, color: AppTheme.slate700),
            ),
          ],
        ),
      ),
    );
  }
}

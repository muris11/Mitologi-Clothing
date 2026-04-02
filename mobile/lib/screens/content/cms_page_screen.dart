import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:provider/provider.dart';
import '../../providers/content_provider.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class CMSPageScreen extends StatefulWidget {
  final String slug;

  const CMSPageScreen({super.key, required this.slug});

  @override
  State<CMSPageScreen> createState() => _CMSPageScreenState();
}

class _CMSPageScreenState extends State<CMSPageScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ContentProvider>().fetchPage(widget.slug);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<ContentProvider>(
      builder: (context, provider, child) {
        final page = provider.currentPage;
        return MitologiScaffold(
          title: page?.title ?? 'Halaman',
          subtitle:
              'Konten informasi dan kebijakan yang mengikuti gaya shop shell.',
          showLogo: false,
          leading: IconButton(
            icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
            onPressed: () => context.popOrGoHome(),
          ),
          body: provider.isLoadingPage
              ? const Center(
                  child: CircularProgressIndicator(color: AppTheme.primary),
                )
              : provider.pageError != null
              ? Center(
                  child: Padding(
                    padding: EdgeInsets.all(
                      ResponsiveHelper.horizontalPadding(context) * 2,
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Container(
                          padding: EdgeInsets.all(
                            ResponsiveHelper.horizontalPadding(context) + 4,
                          ),
                          decoration: BoxDecoration(
                            color: AppTheme.error.withValues(alpha: 0.1),
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(
                            Icons.wifi_off,
                            size: 48,
                            color: AppTheme.error,
                          ),
                        ),
                        const SizedBox(height: 24),
                        const Text(
                          'Masalah Koneksi',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: AppTheme.primary,
                          ),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          provider.pageError!.contains('ClientException')
                              ? 'Gagal terhubung ke server. Pastikan API backend sudah berjalan dan URL base sudah benar.'
                              : 'Terjadi kesalahan saat memuat konten. Silakan coba beberapa saat lagi.',
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontSize: 14,
                            color: AppTheme.slate500,
                            height: 1.5,
                          ),
                        ),
                        const SizedBox(height: 32),
                        ElevatedButton.icon(
                          onPressed: () => provider.fetchPage(widget.slug),
                          icon: const Icon(Icons.refresh, size: 20),
                          label: const Text('Coba Lagi'),
                          style: ElevatedButton.styleFrom(
                            padding: EdgeInsets.symmetric(
                              horizontal:
                                  ResponsiveHelper.horizontalPadding(context) +
                                  8,
                              vertical: 12,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                )
              : page == null
              ? const Center(child: Text('Halaman tidak ditemukan.'))
              : SingleChildScrollView(
                  child: MitologiPageShell(
                    title: page.title,
                    eyebrow: 'Informasi',
                    subtitle:
                        'Konten resmi Mitologi Clothing untuk membantu pelanggan memahami layanan kami.',
                    child: Html(
                      data: page.body,
                      style: {
                        "body": Style(
                          fontSize: FontSize(14),
                          color: AppTheme.slate600,
                          lineHeight: LineHeight(1.6),
                        ),
                        "h1": Style(
                          fontSize: FontSize(24),
                          fontWeight: FontWeight.bold,
                          color: AppTheme.primary,
                        ),
                        "h2": Style(
                          fontSize: FontSize(20),
                          fontWeight: FontWeight.bold,
                          color: AppTheme.primary,
                        ),
                        "h3": Style(
                          fontSize: FontSize(18),
                          fontWeight: FontWeight.bold,
                          color: AppTheme.slate800,
                        ),
                        "a": Style(
                          color: AppTheme.primary,
                          textDecoration: TextDecoration.underline,
                        ),
                      },
                    ),
                  ),
                ),
        );
      },
    );
  }
}

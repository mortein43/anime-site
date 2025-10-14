import Logo from "@/components/Logo/Logo";
import SearchInput from "../SearchInput";
export default function Header() {
  return (
    <header className="flex justify-around items-center 2xl:h-[140px] xl:h-[108px] lg:h-[108px] sm:h-[48px] py-1 sm:py-6 md:pt-6">
      <Logo />
      <div className="flex items-center gap-6">
        <SearchInput />
        <div className="flex gap-6">
          <a
            href="/login"
            className="w-[108px] sm:w-[120px] lg:w-[150px] h-[36px] sm:h-[40px] lg:h-[50px] px-12.5 py-[15.5px] flex justify-center items-center rounded-[8px] border border-[var(--border-line-aqua)] hover:opacity-70 transition"
          >
            Увійти
          </a>
          <a
            href="/register"
            className="w-[108px] sm:w-[120px] lg:w-[150px] h-[36px] sm:h-[40px] lg:h-[50px] py-[15.5px] flex justify-center items-center rounded-[8px] bg-[#4B7FCC] hover:opacity-70 transition"
          >
            Реєстрація
          </a>
        </div>
      </div>
    </header>
  );
}
